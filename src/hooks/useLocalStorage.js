export function getLocalToken() {
  try {
    return localStorage.getItem('auth_token');
  } catch (e) {
    return null;
  }
}

export function setLocalToken(token) {
  try {
    if (token === null || token === undefined) {
      localStorage.removeItem('auth_token');
    } else {
      localStorage.setItem('auth_token', token);
    }
  } catch (e) {
    // ignore
  }
}
