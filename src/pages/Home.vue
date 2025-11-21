<template>
<div class="container">
    <div class="d-flex flex-column align-items-center">
        <div v-if="useAuth.authenticated.value">
            <h1>Welcome "{{ useAuth.user.value.name }}" to Your Digital Wallet</h1>
            <p class="my-2">Manage your finances securely and efficiently.</p>
            <p class="fs-5">Futuristic, Secure, Ready.</p>
            <router-link to="/wallet" class="btn btn-primary">Open Wallet</router-link>
        </div>
        <div v-else>
            <h1>Welcome to {{ app_name }}</h1>
            <p class="my-2">Your gateway to seamless digital transactions.</p>
            <p class="fs-5">Please refresh the page to auto login.</p>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import useAuth from '../useAuth.js';

const user = useAuth.user.value;
const app_name = import.meta.env.VITE_APP_NAME ?? 'Digi-Wallet';

onMounted(() => {
    /*
    if(!useAuth.authenticated.value) {
        useAuth.attempt().catch(() => {
            console.log('User not authenticated');
        });
    }
    */
});

</script>
