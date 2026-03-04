import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import LoginCard from "../../components/ui/LoginCard";
import FormInput from "../../components/ui/FormInput";

/**
 * Pengurus Login Page
 *
 * Halaman login untuk pengurus organisasi, admin, dan kemahasiswaan
 * Dengan form email, password, dan role selection
 */
export default function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [role, setRole] = useState("");
    const navigate = useNavigate();

    function handleSubmit(e) {
        e.preventDefault();
        // Dummy auth: accept anything and navigate to /pengurus
        navigate("/pengurus");
    }

    const roleOptions = [
        { value: "pengurus", label: "Pengurus Organisasi" },
        { value: "admin", label: "Admin Sistem" },
        { value: "kemahasiswaan", label: "Kemahasiswaan" },
    ];

    return (
        <div className="portal-login-wrapper">
            {/* Header Section */}
            <div className="portal-login-header">
                <div className="portal-login-header-content">
                    <div className="portal-login-header-icon">👤</div>
                    <div>
                        <h1 className="portal-login-header-title">
                            Sistem Kemahasiswaan
                        </h1>
                        <p className="portal-login-header-subtitle">
                            Universitas Klabat
                        </p>
                    </div>
                </div>
            </div>

            {/* Login Container */}
            <div className="portal-login-container">
                <LoginCard
                    title="Selamat Datang"
                    subtitle="Silakan masuk ke akun Anda"
                >
                    <form onSubmit={handleSubmit}>
                        {/* Email Input */}
                        <FormInput
                            label="Email"
                            type="email"
                            icon="✉️"
                            placeholder="Masukkan email"
                            name="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                        />

                        {/* Password Input */}
                        <FormInput
                            label="Password"
                            type="password"
                            icon="🔒"
                            placeholder="Masukkan password"
                            name="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            required
                        />

                        {/* Role Select */}
                        <FormInput
                            label="Pilih Role"
                            type="select"
                            icon="🎯"
                            placeholder="Pilih role Anda"
                            options={roleOptions}
                            name="role"
                            value={role}
                            onChange={(e) => setRole(e.target.value)}
                            required
                        />

                        {/* Submit Button */}
                        <button type="submit" className="portal-login-btn">
                            Masuk
                        </button>
                    </form>
                </LoginCard>

                {/* Footer Info */}
                <div className="portal-login-page-footer">
                    <p className="portal-login-footer-main">
                        Sistem Administrasi & Kontrol Organisasi Mahasiswa
                    </p>
                    <p className="portal-login-footer-sub">
                        Departemen Kemahasiswaan Universitas Klabat
                    </p>
                </div>
            </div>
        </div>
    );
}
