<template>
<div class="border rounded rounded-3 mb-4">
    <h2 class="p-2 bg-light rounded rounded-3">Recent Transactions</h2>
    <div class="container mb-4">
        <div class="row mb-2" v-for="txn in transactions.data" :key="txn.id">
            <div class="col-sm-4">{{ txn.trans_date }}</div>
            <!-- <div>{{ txn.trans_type.toUpperCase() }}</div> -->
            <div class="col-sm-2">
                <strong v-if="txn.sender_id === user.id">To:</strong>
                <strong v-else>From:</strong>
                {{ txn.sender_id === user.id ? txn.receiver.name : txn.sender.name }}
            </div>
            <div :class="{'col-sm-3 text-danger': txn.sender_id === user.id, 'col-sm-3 text-success': txn.receiver_id === user.id}">
                {{ txn.sender_id === user.id ? '-' : '+' }}{{ txn.amount }}
                <small class="text-muted" v-if="txn.sender_id === user.id">| Commission: {{ txn.commission_fee }}</small>
            </div>
            <div class="text-muted col-sm-3">Balance:
                {{ txn.sender_id === user.id ? txn.sender_balance_before : txn.receiver_balance_before }}
                &raquo;
                {{ txn.sender_id === user.id ? txn.sender_balance_after : txn.receiver_balance_after }}
            </div>
        </div>
        <div v-if="!transactions.data || transactions.data.length === 0" class="p-4">
            No transactions found.
        </div>
        <!-- pagging links -->
        <div class="d-flex justify-content-center mt-5" v-if="transactions.last_page > 1">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: !transactions.prev_page_url }">
                        <a class="page-link" href="#" @click.prevent="fetchPage(transactions.current_page - 1)">Previous</a>
                    </li>
                    <li class="page-item" v-for="page in transactions.last_page" :key="page" :class="{ active: page === transactions.current_page }">
                        <a class="page-link" href="#" @click.prevent="fetchPage(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: !transactions.next_page_url }">
                        <a class="page-link" href="#" @click.prevent="fetchPage(transactions.current_page + 1)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
</template>

<script setup>
import { computed, defineEmits } from 'vue';
import useAuth from '../useAuth.js';

const props = defineProps({ transactions: Array });
const emit = defineEmits(['navPageUpdate']);
const user = useAuth.user.value;

function fetchPage(page) {
    if (page < 1 || page > props.transactions.last_page) {
        return;
    }
    axios.get('/api/transactions?page=' + page)
        .then(resp => {
            // emit event to parent to update transactions
            emit('navPageUpdate', resp.data.transactions);
        });
}

</script>
