import axios from 'axios';

const base = process.env.REACT_APP_API_URL || 'http://localhost:8000/api';

export default {
  async login(credentials) {
    // Try real API, fallback to mock
    try {
      const res = await axios.post(`${base}/auth/login`, credentials);
      return res.data;
    } catch (err) {
      // Fallback for dev: return fake token/user
      return {
        token: 'dev-token',
        user: { id: 1, name: 'Dev User', role: 'kemahasiswaan' },
      };
    }
  },

  async logout(token) {
    try {
      await axios.post(`${base}/auth/logout`, null, {
        headers: { Authorization: `Bearer ${token}` },
      });
    } catch (err) {
      // ignore in dev
    }
  },

  async me(token) {
    try {
      const res = await axios.get(`${base}/auth/me`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      return res.data;
    } catch (err) {
      // dev fallback
      return { user: { id: 1, name: 'Dev User', role: 'kemahasiswaan' } };
    }
  },
};
