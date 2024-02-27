<template>
    <v-dialog v-model="dialog" max-width="500px" scrollable content-class="scanner-dialog" persistent>
        <v-card class="scanner-dialog-card">
            <v-card-title>
                <span class="headline"> Scan </span>

                <v-spacer></v-spacer>						

                <v-btn icon dark class="btn-close" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text class="mb-0" style="padding: 16px;">
                <StreamBarcodeReader
                    ref="scanner"
                    @decode="onDecode"
                    @loaded="onLoaded">
                </StreamBarcodeReader>
                Input Value: {{ getCode || "Nothing" }}
            </v-card-text>            
        </v-card>
    </v-dialog>
</template>

<script>
import { StreamBarcodeReader } from "vue-barcode-reader";

export default {
    name: 'ConfirmDialog',
    props: ['dialogData', 'scannedCode'],
    data: () => ({
        getCode: '',
        id: null,
        hasLoaded: false
    }),
    components: {
        StreamBarcodeReader
    },
    computed: {
        dialog: {
            get () {
                return this.dialogData
            },
            set (value) {
                if (!value) {
                    this.$emit('update:dialogData', false)
                } else {
                    this.$emit('update:dialogData', true)
                }
            }
        },
        code: {
            get() {
                return this.scannedCode
            },
            set(value) {
                this.$emit('update:scannedCode', value)
            }
        }
    },
    watch: {
        dialog(val) {
            if (val) {
                this.start()
            }
        },
        getCode(val) {
            if (val !== '') {
                this.code = val
            }
        }
    },
    methods: {    
        onDecode(result) {
            this.getCode = result
            console.log(this.getCode, 'result');

            if (this.getCode !== '') {
                this.code = this.getCode
            }

            // if (result !== undefined) {
            //     this.code = result

            //     setTimeout(() => {                    
            //         this.close()
            //     }, 5000);
            // }
        },
        onLoaded() {
            console.log("load");
            this.hasLoaded = true
        },
        close() {
            this.$emit('close')
            this.$refs.scanner.codeReader.stream.getTracks().forEach(function (track) { track.stop() })
            this.hasLoaded = false
        },
        beforeDestroy() {
            this.$refs.scanner.codeReader.stream.getTracks().forEach(function (track) { console.log(track); })
            this.hasLoaded = false
        },
        start() {
            if (this.$refs.scanner !== undefined) {
                this.$refs.scanner.start()
                this.getCode = ''
            }            
        }
    },
    mounted() {
        setInterval(() => {
            if (this.camera === "front") {
                this.camera = "back";
            } else {
                this.camera = "front";
            }
        }, 5000);
    },
    updated() {}
}
</script>

<style lang="scss">
@import '@/assets/scss/buttons.scss';
@import '@/assets/scss/pages_scss/dialog/globalDialog.scss';
</style>