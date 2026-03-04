import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from '../components/layout/Header';
import Sidebar from '../components/Sidebar';
import './AdminLayout.css';

export default function AdminLayout() {
  return (
    <div className="layout admin-layout">
      <Header role="admin" />
      <div className="layout-body">
        <aside className="layout-sidebar">
          <Sidebar role="admin" />
        </aside>
        <main className="layout-content">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
