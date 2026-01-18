import React from "react";
import { Outlet } from "react-router-dom";
import Header from "../components/layout/Header";
import Sidebar from "../components/layout/Sidebar";
import { useAuth } from "../auth/useAuth";
import "./KemahasiswaanLayout.css"; // optional

export default function KemahasiswaanLayout() {
    const { user } = useAuth();

    return (
        <div className="layout kemahasiswaan-layout">
            <Header role="kemahasiswaan" />
            <div className="layout-body">
                <aside className="layout-sidebar">
                    <Sidebar
                        role="kemahasiswaan"
                        items={[
                            { to: "/kemahasiswaan", label: "Dashboard" },
                            {
                                to: "/kemahasiswaan/manajemen-organisasi",
                                label: "Manajemen Organisasi",
                            },
                            {
                                to: "/kemahasiswaan/pendaftaran",
                                label: "Pendaftaran",
                            },
                            // add more items
                        ]}
                    />
                </aside>
                <main className="layout-content">
                    {/* Outlet renders nested routes (Dashboard, pages) */}
                    <Outlet />
                </main>
            </div>
        </div>
    );
}
