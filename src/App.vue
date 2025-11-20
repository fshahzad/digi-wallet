<template>
    <div id="app">
        <header>
            <h1>Welcome to Digi-Wallet</h1>
            <p>Your digital wallet solution</p>
        </header>
        <main>
            <div class="welcome-content">
                <h2>Get Started</h2>
                <p>Manage your finances securely and efficiently.</p>
                <button @click="handleGetStarted">Get Started</button>
            </div>
        </main>
    </div>
</template>

<script>
export default {
    name: 'App',
    methods: {
        handleGetStarted() {
            console.log('Getting started...');
        }
    }
}
</script>

<style scoped>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}

header {
    margin-bottom: 40px;
}

h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.welcome-content {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

button {
    background-color: #42b983;
    color: white;
    border: none;
    padding: 12px 30px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
}

button:hover {
    background-color: #359268;
}
</style>
