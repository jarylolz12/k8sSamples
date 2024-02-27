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
        Print Label
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
        <div class="labelMain">
          <div class="qrDiv d-flex align-center justify-center flex-column">
            <div class="text-center px-5 ">
              <img class="flex-grow-1" src="" width="100%" height="auto" alt="" ref="qrImage">
            </div>
            <span ref="labelField">{{this.label}}</span>
          </div>
          <div class="text-center blue--text text--darken-4 font-weight-bold">
            <div class="mt-4 propertyOf">Property of</div>
            <div ref="pdfCompanyName"></div>
          </div>
        </div>
        <div class="labelExtra text-center white--text blue darken-4">
          <div ref="pdfDateField"></div>
          <div ref="pdfUser"></div>
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

export default {
  name: "PrintLabelDialog",
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
    async print(palletLabel){
      if (!this.label && !palletLabel) throw new Error("No pallet label!");
      this.$refs.labelField.innerText = this.label ?? palletLabel;
      this.$refs.pdfCompanyName.innerText = this.companyName
      this.$refs.pdfUser.innerText = this.user.name
      this.$refs.pdfDateField.innerText = this.formatTimeAndDateTogether(new Date);
      this.$refs.qrImage.src = await QRCode.toDataURL(this.label ?? palletLabel, {type: 'image/jpeg', margin: 0});
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
  border-radius: 1rem;
  border: 3px solid darkslategray;
  width: 4in;
  height: 2in;
  display: grid;
  grid-template-rows: 3fr 1fr;
  .labelMain{
    display: grid; grid-template-columns: 9fr 11fr
  }
  .propertyOf{
    font-size: .9rem;
  }
}
</style>