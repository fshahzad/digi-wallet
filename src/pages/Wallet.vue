<template>
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1>Wallet</h1>
        <span class="badge rounded-pill bg-primary fs-5">
            <strong>Balance:</strong> {{ balanceFormatted }}
        </span>
    </div>
    <div>
        <TransferForm @transfer_done="fetchTransactions" />
    </div>
    <div>
        <TransactionsList :transactions="transactions" @navPageUpdate="pageDataUpdated" />
    </div>
</div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import TransferForm from '../components/TransferForm.vue';
import TransactionsList from '../components/TransactionList.vue';
import echo from '../echo.js';
import useAuth from '../useAuth.js';

const balance = ref('0.00');
const transactions = ref([]);
const user = useAuth.user.value;

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

onMounted(() => {
    balance.value = user.balance || '0.00';
    fetchTransactions();
    /*
    // subscribe to private channel for current user
    // assume you have current user id available, e.g. in window.App.user.id
    //const userId = window.App?.user?.id
    const userId = useAuth.user.id;
    if (userId) {
        echo.private(`user.${userId}`).listen(
            '.TransferEvent', (payload) => {
                // payload.transaction
                const txn = payload.transaction
                // update balance if the event is for this user
                if (txn.sender_id === userId) {
                    balance.value = txn.sender_balance_after
                }
                if (txn.receiver_id === userId) {
                    balance.value = txn.receiver_balance_after
                }
                // update transactions list (prepend)
                transactions.value.unshift(txn)
            }
        );
    }
    */
});

const balanceFormatted = computed(() => {
  return parseFloat(balance.value).toFixed(2);
})
</script>
