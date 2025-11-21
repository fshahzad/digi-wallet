<template>
<div class="border mb-4 rounded rounded-3">
    <h2 class="p-2 bg-light rounded rounded-3">Send Money</h2>
    <div class="container mb-4">
        <div class="row">
            <form @submit.prevent="submit" class="col-md-6">
                <div class="mb-3 mt-3">
                    <label class="form-label mb-0">Recipient ID <small class="text-muted fst-italic">(Numbers only)</small></label>
                    <input v-model="receiverId" type="number" required class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label mb-0">Amount <small class="text-muted fst-italic">(Type numbers only without comma, rounded to two decimals)</small></label>
                    <input v-model="amount" type="number" step="0.01" min="1" required class="form-control" />
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button :disabled="loading" class="btn btn-success btn-lg">Send</button>
                    <div v-if="error" style="color:red">{{ error }}</div>
                    <div v-if="success" style="color:green">{{ success }}</div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';
const receiverId = ref('');
const amount = ref('');
const loading = ref(false);
const error = ref(null);
const success = ref(null);
const emit = defineEmits(['transfer_done']);

async function submit() {
    error.value = null;
    success.value = null;
    loading.value = true;
    try {
        const resp = await axios.post('/api/transactions', {
            receiver_id: receiverId.value,
            amount: amount.value
        });
        success.value = 'Transfer successful.';
        // bubble up the event via emit so parent can refresh
        emit('transfer_done', resp.data);
        receiverId.value = '';
        amount.value = '';
    } catch (e) {
        if (e.response && e.response.data) {
            error.value = e.response.data.message || JSON.stringify(e.response.data)
        } else {
            error.value = e.message;
        }
    } finally {
        loading.value = false;
        window.setTimeout(() => {
            success.value = null;
            error.value = null;
        }, 2000);
    }
}
</script>
