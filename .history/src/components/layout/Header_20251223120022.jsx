import React from "react";

export default function Header({ role, onMenuClick }) {
    return (
        <header className="fixed top-0 left-0 right-0 bg-white shadow z-40">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
                <div className="flex items-center gap-3">
                    <button
                        onClick={onMenuClick}
                        aria-label="Toggle menu"
                        className="p-2 rounded-md hover:bg-gray-100"
                    >
                        <svg
                            className="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                    <h1 className="text-lg font-semibold">
                        {role === "pengurus" ? "Panel Pengurus" : "UFO"}
                    </h1>
                </div>

                <div className="flex items-center gap-4">
                    <div className="text-sm text-gray-600">Selamat datang</div>
                </div>
            </div>
        </header>
    );
}
