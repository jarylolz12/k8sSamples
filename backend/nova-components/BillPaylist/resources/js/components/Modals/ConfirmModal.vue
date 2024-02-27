<template>
    <div @click="closeModal" class="modal-overlay">
        <div :class="`modal confirm-modal ${confirmClass}`">
            <h6 class="text-white py-16">{{ title }}</h6>
            <slot name="content" v-bind:getItem="item"></slot>
            <div class="btn-group-div" v-if="!marking">
                <button @click="$emit('confirm')" class="btn btn-default bg-success text-white py-0 leading-3 yes-btn">Yes</button>
                <button @click="closeModal" class="btn btn-default bg-danger text-white py-0 leading-3 no-btn">No</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    components:{},
    props: ['modal', 'title', 'item', 'marking', 'confirmClass'],
    mounted() {},
    methods: {
        closeModal() {
            this.$emit('update:confirmClass', 'slide-out-elliptic-top-bck')
            setTimeout(() => {
            this.$emit('update:confirmClass', '')
              this.$emit('update:modal', false) 
            },750)
        }
    }
}
</script>
<style scoped>
.confirm-modal {
    font-family: 'Montserrat', sans-serif;
}
.btn-group-div .yes-btn:hover{
    background-color: #147a4e;
}
.btn-group-div .no-btn:hover {
    background-color: #762c2c;
}
.modal-overlay {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  transition: 0.3s background-color;
  background-color: #000000da;
  z-index: 100;
}

.modal {
  text-align: center;
  background-color: white;
  margin-top: 10%;
  border-radius: 20px;
  height: 248px;
  box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
}
.modal p {
    padding-left: 16px;
    padding-right: 16px;
}
.close {
  margin: 10% 0 0 16px;
  cursor: pointer;
}

.close-img {
  width: 25px;
}

.check {
  width: 150px;
}

h6 {
  font-weight: 500;
  font-size: 28px;
  border-top-left-radius: 17px;
  border-top-right-radius: 17px;
  padding-top: 16px;
  padding-bottom: 16px;
  background-color: rgb(37, 45, 55);
}

p {
  font-size: 16px;
  margin: 20px 0;
}

button {
  width: 150px;
  height: 40px;
  color: white;
  font-size: 14px;
  border-radius: 16px;
  margin-top: 50px;
}
</style>