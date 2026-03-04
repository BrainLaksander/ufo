import React from "react";

/**
 * FormInput Component
 * 
 * Input field reusable dengan icon dan styling konsisten
 * Untuk login, register, atau form lainnya
 * 
 * Props:
 * - label (string): Label input
 * - type (string): Tipe input (text, email, password, select)
 * - icon (string): Emoji icon
 * - placeholder (string): Placeholder text
 * - options (array): Untuk select type
 * - onChange (function): Callback saat input berubah
 * - value (string): Nilai input
 * - required (boolean): Input required
 */
export default function FormInput({
    label,
    type = "text",
    icon = "📝",
    placeholder = "",
    options = [],
    onChange,
    value,
    required = false,
    name = "",
    id = name,
}) {
    return (
        <div className="portal-form-group">
            {label && <label htmlFor={id} className="portal-form-label">{label}</label>}
            
            <div className="portal-form-input-wrapper">
                {icon && <span className="portal-form-icon">{icon}</span>}

                {type === "select" ? (
                    <select
                        id={id}
                        name={name}
                        className="portal-form-input portal-form-select"
                        onChange={onChange}
                        value={value}
                        required={required}
                    >
                        <option value="">{placeholder}</option>
                        {options.map((opt) => (
                            <option key={opt.value} value={opt.value}>
                                {opt.label}
                            </option>
                        ))}
                    </select>
                ) : (
                    <input
                        id={id}
                        type={type}
                        name={name}
                        className="portal-form-input"
                        placeholder={placeholder}
                        onChange={onChange}
                        value={value}
                        required={required}
                    />
                )}
            </div>
        </div>
    );
}
