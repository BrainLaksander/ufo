import React, { useState } from "react";
import { Outlet } from "react-router-dom";
import Header from "../components/layout/Header";
import BurgerMenu from "../components/layout/BurgerMenu";

export default function PengurusLayout() {
    const [menuOpen, setMenuOpen] = useState(false);

    return (
        <div className="min-h-screen bg-gray-50">
            <Header role="pengurus" onMenuClick={() => setMenuOpen(true)} />

            <BurgerMenu open={menuOpen} onClose={() => setMenuOpen(false)} />

            <div className="pt-14">
                <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}
