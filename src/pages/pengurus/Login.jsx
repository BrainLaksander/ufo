import React, { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const navigate = useNavigate();

    function handleSubmit(e) {
        e.preventDefault();
        // dummy auth: accept anything and navigate to /pengurus
        navigate("/pengurus");
    }

    return (
        <div className="max-w-md mx-auto bg-white p-6 rounded shadow">
            <h2 className="text-xl font-semibold mb-4">Login Pengurus</h2>
            <form onSubmit={handleSubmit} className="space-y-3">
                <div>
                    <label className="block text-sm">Email</label>
                    <input
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        className="mt-1 block w-full border px-3 py-2 rounded"
                        placeholder="pengurus@contoh.test"
                    />
                </div>
                <div>
                    <label className="block text-sm">Password</label>
                    <input
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        className="mt-1 block w-full border px-3 py-2 rounded"
                        placeholder="••••••••"
                    />
                </div>
                <div className="flex justify-end">
                    <button className="px-4 py-2 bg-blue-600 text-white rounded">
                        Login
                    </button>
                </div>
            </form>
        </div>
    );
}
