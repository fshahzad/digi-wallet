import { computed, reactive } from 'vue'

const state = reactive({
    authenticated: false,
    user: {}
})

const useAuth = () => {
    const authenticated = computed(() => state.authenticated)
    const user = computed(() => state.user)

    const setAuthenticated = (authenticated) => {
        state.authenticated = authenticated
    }

    const setUser = (user) => {
        state.user = user
    }

    const login = async (credentials) => {
        try {
            //await axios.post('/login', credentials);
            await axios.get('/sanctum/csrf-cookie');
            let response = await axios.get('/api/current-user', {
                withCredentials: true,
                headers: {
                    'Accept': 'application/json',
                }
            });
            return Promise.resolve(response.data.user);
        } catch (e) {
            return Promise.reject(e.response?.data?.errors || e);
        }
    }

    const attempt = async (credentials = []) => {
        await login(credentials).then(data => {
            setAuthenticated(true);
            setUser(data);
        })
        .catch(() => {
            setAuthenticated(false);
            setUser({});
        });
    }

    return {
        authenticated,
        user,
        attempt
    }
}

export default useAuth();
