import React from "react";

/**
 * LoginCard Component
 *
 * Reusable card component untuk login form
 * Dengan icon, title, subtitle, dan custom content
 *
 * Props:
 * - title (string): Judul card
 * - subtitle (string): Subjudul card
 * - children: Form content
 */
export default function LoginCard({ title, subtitle, children }) {
    return (
        <div className="portal-login-card">
            {/* Card Header */}
            <div className="portal-login-card-header">
                <h2 className="portal-login-card-title">{title}</h2>
                <p className="portal-login-card-subtitle">{subtitle}</p>
            </div>

            {/* Form Content */}
            <div className="portal-login-form">{children}</div>
        </div>
    );
}
