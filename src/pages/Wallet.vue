<template>
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1>Wallet</h1>
        <span class="badge rounded-pill bg-primary fs-5">
            <strong>Balance:</strong> {{ balanceFormatted }}
        </span>
    </div>
    <div>
        <TransferForm @transfer_done="updateTransactions" />
    </div>
    <!-- Alert placeholder for realtime transfer notifications -->
    <div v-if="showAlert" class="mt-3">
        <div :class="['alert', `alert-${alertVariant}`, 'alert-dismissible', 'fade', showAlert ? 'show' : '']" role="alert">
            <strong>{{ alertTitle }}</strong>
            <div v-html="alertMessage"></div>
            <button type="button" class="btn-close" aria-label="Close" @click="dismissAlert"></button>
        </div>
    </div>
    <div>
        <TransactionsList :transactions="transactions" @navPageUpdate="pageDataUpdated" />
    </div>
</div>
</template>

<script setup>
import { ref, onMounted, computed, onUnmounted } from 'vue';
import TransferForm from '../components/TransferForm.vue';
import TransactionsList from '../components/TransactionList.vue';
import echo from '../echo.js';
import useAuth from '../useAuth.js';

const balance = ref('0.00');
const transactions = ref([]);
// alert state for realtime notifications
const showAlert = ref(false);
const alertMessage = ref('');
const alertTitle = ref('');
const alertVariant = ref('info');
let alertTimer = null;
const user = useAuth.user.value;
const userId = user.id;

function fetchTransactions() {
    if(!user || !user.id) {
        return;
    }
    axios.get('/api/transactions')
        .then(resp => {
            balance.value = resp.data.balance;
            transactions.value = resp.data.transactions;
            //console.log('Fetched transactions', transactions.value);
        }
    );
}

function pageDataUpdated(newTransactions) {
    transactions.value = newTransactions;
}

function updateTransactions(data) {
    // prepare a user-friendly message
    const receiver_name = data.receiver.name;
    const amount = parseFloat(data.amount).toFixed(2);
    alertTitle.value = 'Money Sent';
    alertVariant.value = 'info';
    alertMessage.value = `You sent <strong>${amount}</strong> to <strong>${receiver_name}</strong>.`;
    // show alert and auto-dismiss
    showAlert.value = true;
    if (alertTimer) { clearTimeout(alertTimer); }
    alertTimer = setTimeout(() => { showAlert.value = false; alertTimer = null; }, 10000);
    fetchTransactions();
}

onMounted(() => {
    balance.value = user.balance || '0.00';
    fetchTransactions();
    // subscribe to private channel for current user
    if (useAuth.authenticated.value && userId) {
        console.log('Subscribing to private channel: user.' + userId);
        try {
            echo.private(`user.${userId}`).listen(
                '.WalletTransfer', (payload) => {
                    const txn = payload;
                    //console.log(txn);
                    // update balance if the event is for this user
                    balance.value = txn.receiver_balance_after;
                    // update transactions list (prepend)
                    transactions.value.data.unshift(txn);
                    // prepare a user-friendly message
                    const sender_name = txn.sender.name;
                    const amount = parseFloat(txn.amount).toFixed(2);
                    alertTitle.value = 'Money Received';
                    alertVariant.value = 'success';
                    alertMessage.value = `You received <strong>${amount}</strong> from <strong>${sender_name}</strong>.`;
                    // show alert and auto-dismiss
                    showAlert.value = true;
                    if (alertTimer) { clearTimeout(alertTimer); }
                    alertTimer = setTimeout(() => { showAlert.value = false; alertTimer = null; }, 10000);
                }
            );
        } catch (error) {
            console.error('Error subscribing to private channel:', error);
        }
    }
});

onUnmounted(() => {
    if (userId) {
        console.log('Unsubscribing to private channel: user.' + userId);
        echo.leave(`user.${userId}`);
    }
    if (alertTimer) {
        clearTimeout(alertTimer);
        alertTimer = null;
    }
});

function dismissAlert() {
    showAlert.value = false;
    if (alertTimer) {
        clearTimeout(alertTimer);
        alertTimer = null;
    }
}

const balanceFormatted = computed(() => {
    return parseFloat(balance.value).toFixed(2);
});
</script>
