<script>
import BaseNotification from "@/components/Notifications/Types/BaseNotification";

export default {
  name: "ImportPurchaseOrdersSuccessNotification",
  extends: BaseNotification,
  methods:{
    onOpen(){
      this.goToRoute('/pos')
    }
  }
}
</script>

<style scoped>

</style>