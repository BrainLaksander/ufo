import React from "react";
import { Link } from "react-router-dom";

export default function BurgerMenu({ open, onClose }) {
    return (
        <div
            className={`fixed inset-y-0 left-0 z-50 w-64 bg-white shadow transform transition-transform duration-300 ${
                open ? "translate-x-0" : "-translate-x-full"
            }`}
        >
            <div className="p-4 border-b">
                <div className="flex items-center justify-between">
                    <div className="text-lg font-semibold">Menu</div>
                    <button onClick={onClose} aria-label="close" className="p-1">
                        ✕
                    </button>
                </div>
            </div>
            <nav className="p-4">
                <ul className="space-y-2">
                    <li>
                        <Link to="/pengurus" onClick={onClose} className="block">
                            Dashboard
                        </Link>
                    </li>
                    <li>
                        <Link to="/pengurus/profil" onClick={onClose} className="block">
                            Profil Organisasi
                        </Link>
                    </li>
                    <li>
                        <Link to="/pengurus/login" onClick={onClose} className="block">
                            Login
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>
    );
}
