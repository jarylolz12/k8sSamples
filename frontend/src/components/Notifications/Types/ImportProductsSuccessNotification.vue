<script>
import BaseNotification from "@/components/Notifications/Types/BaseNotification";

export default {
  extends: BaseNotification,
  name: "ImportProductsSuccessNotification",
  methods:{
    onOpen(){
      this.goToRoute('/products')
    }
  }
}
</script>

<style scoped>

</style>