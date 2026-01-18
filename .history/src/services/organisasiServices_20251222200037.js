import api from "./api";

export const fetchOrganisasi = (params) =>
    api.get("/organisasi", { params }).then((r) => r.data);
export const fetchOrganisasiById = (id) =>
    api.get(`/organisasi/${id}`).then((r) => r.data);
export const createOrganisasi = (payload) =>
    api.post("/organisasi", payload).then((r) => r.data);
// export update/delete as needed
