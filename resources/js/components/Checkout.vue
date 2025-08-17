<template>
  <div>
    <payment-form 
      :total="cartTotal" 
      :order-id="order.id"
      @payment-success="handleSuccess"
      @payment-error="handleError"
    />
    
    <div v-if="tracking" class="tracking-info">
      <h3>Your Order is On the Way!</h3>
      <tracking-status :tracking="tracking" />
    </div>
  </div>
</template>

<script>
import PaymentForm from './PaymentForm.vue';
import TrackingStatus from './TrackingStatus.vue';

export default {
  components: { PaymentForm, TrackingStatus },
  data() {
    return {
      order: null,
      tracking: null
    }
  },
  methods: {
    async handleSuccess(payment) {
      // Create order
      const { data } = await axios.post('/api/orders', {
        items: this.cartItems,
        payment_method: payment.paymentMethod
      });
      
      this.order = data.order;
      this.tracking = data.tracking;
    },
    handleError(error) {
      this.$toast.error(error);
    }
  }
}
</script>