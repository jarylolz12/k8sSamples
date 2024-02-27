<template>
<div>
  <v-dialog
      v-model="show"
      max-width="720"
      scrollable
      :fullscreen="this.$vuetify.breakpoint.smAndDown"
  >
    <v-card>
      <v-card-title class="text-h5">
        Print Location Label
        <button icon dark class="btn-close" @click="$emit('close')">
          <v-icon>mdi-close</v-icon>
        </button>
      </v-card-title>
      <v-card-text class="renderPdfDiv" ref="renderPdfDiv"/>
      <v-card-actions class="justify-end">
        <v-btn class="btn-blue" @click.stop="downloadDocAsPDF">Pdf<v-icon right>mdi-file</v-icon></v-btn>
        <v-btn class="btn-blue" @click.stop="downloadDocAsImage">Image<v-icon right>mdi-image</v-icon></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  <div class="pdfParentDiv">
    <div>
      <div ref="toPdf" class="overflow-hidden toPdfDiv">
        <div class="labelMain pl-2">
          <div class="qrDiv d-flex align-center justify-center flex-column">
            <div>
              <img width="100%" height="auto" alt="" ref="qrImage">
            </div>
            <div ref="labelField" class="font-weight-black text-capitalize">{{this.label}}</div>
          </div>
          <div class="text-center d-flex align-center justify-center flex-column text--darken-4">
            <div class="px-2 barcodeImgDiv">
              <img width="100%" ref="barcodeDiv"/>
            </div>
            <span class="font-weight-bold text-h6" ref="locationTextDiv"></span>
          </div>
        </div>
        <div class="labelExtra white--text d-flex align-center px-2 black">
          <div ref="skus"></div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import QRCode from "qrcode";
import html2canvas from "html2canvas";
import {jsPDF} from "jspdf";
import PDFObject from "pdfobject";
import inventoryGlobalMethods from "@/utils/inventoryMethods/inventoryGlobalMethods";
import JsBarcode from "jsbarcode";

export default {
  name: "PrintLocationLabelDialog",
  props:["showDialog","label"],
  data(){
    return{
      doc: {},
      user: null,
    }
  },
  mounted() {
    this.user = JSON.parse(this.$store.getters.getUser);
    this.emitMethods();
  },
  methods:{
    ...inventoryGlobalMethods,
    emitMethods() {
      this.$emit("init", {
        print: (label) => this.print(label)
      });
    },
    getFormattedLoc(loc){
      let locLabel = `${loc.row}-${loc.rack}-${loc.shelf}`;
      if (loc.type === 'staging') {
        locLabel = `${loc.code}`;
        if (loc.position) locLabel += `-${loc.position}`;
      }

      return locLabel
    },
    async print(locationLabel){
      if (!this.label && !locationLabel) throw new Error("No pallet label!");
      const formattedLoc = this.getFormattedLoc(locationLabel)
      this.$refs.labelField.innerText = locationLabel.type;
      this.$refs.locationTextDiv.innerText = formattedLoc;
      JsBarcode(this.$refs.barcodeDiv, formattedLoc,{
        displayValue: false,
        margin: 0,
        textMargin: 12,
        width: 1,
        height: 84,
        fontSize: 15
      });
        this.$refs.skus.innerText = locationLabel.default_sku ? this.formatDefaultSKUs(locationLabel.default_sku) : "";
      this.$refs.qrImage.src = await QRCode.toDataURL(formattedLoc, {type: 'image/jpeg', margin: 0});
      let cvs = await html2canvas(this.$refs.toPdf);
      const canvasImage = cvs.toDataURL();
      this.doc.imageUrl = canvasImage;
      let doc = new jsPDF({
        orientation: 'landscape',
        unit: "in",
        format: [4,2]
      });

      doc.setFontSize(30);
      await doc.addImage(canvasImage,.1,.1,3.8,1.8);
      this.doc.pdf = doc;
      this.show = true;
      this.$nextTick(() => {
        PDFObject.embed(doc.output('datauristring'),this.$refs.renderPdfDiv,{
          fallbackLink: `<p>This browser does not support inline PDFs. Please use the buttons bellow to download.</p>`
        })
      })
    },
    formatDefaultSKUs(skus){
      skus = JSON.parse(skus);
      return 'Default SKUs: ' + (skus.length > 2 ? skus.slice(0,2).join(', ') + `, +${skus.length-2} more` : skus.join(', '))
    },
    downloadDocAsImage(){
      const imageUrl = this.doc?.imageUrl;
      if (imageUrl){
        const link = document.createElement("a");
        link.download = 'label.png';
        link.href = imageUrl;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }
    },
    downloadDocAsPDF(){
      const pdf = this.doc?.pdf;
      if (pdf){
        pdf.save('Label.pdf')
      }
    }
  },
  computed:{
    companyName(){
      const customer = this.user?.customers_api.find(c => c.id == this.user.default_customer_id)
      return customer.company_name || 'Company'
    },
    show: {
      get() {
        return this.showDialog
      },
      set(value) {
        this.$emit('update:showDialog', value)
      }
    },
  }
}
</script>

<style scoped lang="scss">
.pdfParentDiv{
  position:absolute; top: -999999em
}

.renderPdfDiv{
  height: 4in;
}
.toPdfDiv {
  border-radius: .75rem;
  border: 3px solid darkslategray;
  width: 4in;
  height: 2in;
  display: grid;
  grid-template-rows: 4fr 1fr;
  .labelMain{
    display: grid; grid-template-columns: 1fr 3fr
  }
  .barcodeImgDiv{
    max-height: 84pt;
  }
  .propertyOf{
    font-size: .9rem;
  }
}
</style>