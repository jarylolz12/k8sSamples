<template>
    <div class="flex border-b border-40 remove-bottom-border">
        <div class="w-1/4 py-4"><h4 class="font-normal text-80">Instructions</h4></div>
        <div class="w-3/4 py-4 break-words">
            <div style="margin-bottom:16px" v-if="show_content">
                <a :class="'editor-btn'+(bold?' active':'')" href="#" @click.prevent="bold=(bold?false:true)">Bold</a>
                <a :class="'editor-btn'+(italic?' active':'')" href="#" @click.prevent="italic=(italic?false:true)">Italic</a>
                <a :class="'editor-btn'+(col==2?' active':'')" href="#" @click.prevent="col=(col==1?2:1)">Split</a>
            </div>
            <div>
                <a aria-role="button" class="cursor-pointer dim inline-block text-primary font-bold" @click.prevent="show_content=true" v-if="!show_content">
                    Show Content
                </a>
                <div class="flex" v-if="show_content">
                    <div :class="'flex-auto markdown leading-normal whitespace-pre-wrap'+(bold ? ' font-bold' : '')+(italic ? ' italic' : '')" v-for="(item,index) in instructions_arr" v-html="item"></div>
                </div>
                <a aria-role="button" class="cursor-pointer dim inline-block text-primary font-bold mt-6" @click.prevent="show_content=false" v-if="show_content">
                    Hide Content
                </a>  
            </div>
        </div>
    </div>

</template>

<style lang="scss" scoped>
    a.editor-btn{
        display:inline-flex;
        padding: 8px 12px;
        background:#ededed;
        color:#242424;
        text-decoration:none;
        border-radius: 5px;
        &.active{
            background: #4099de!important;
            color:#FFF!important;
        }
    }
</style>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    data(){
        return{
            show_content: false,
            instructions_arr: [],
            col: 1,
            bold: false,
            italic: false
        }
    },
    watch: {
        col(){
            this.activateCol();
        }
    },
    methods: {
        activateCol(){
            this.instructions_arr = [];

            let len = this.field.instructions.length;

            for(var i=0; i < this.col; i++ ){
                if( i == 0 ){
                    this.instructions_arr.push(this.field.instructions.substring(0, len / this.col));
                }else{
                    this.instructions_arr.push(this.field.instructions.substring(len / this.col));
                }
            }
        }
    },
    mounted(){
        
        this.activateCol();

        document.addEventListener('keydown',event=>{
            if ((event.ctrlKey || event.metaKey) && event.keyCode == 66) {
                document.execCommand('bold');
            }

            if ((event.ctrlKey || event.metaKey) && event.keyCode == 73) {
                document.execCommand('bold');
            }
        })
    }
}
</script>
