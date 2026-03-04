import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from '../components/layout/Header';
import Sidebar from '../components/Sidebar';
import './MahasiswaLayout.css';

export default function MahasiswaLayout() {
  return (
    <div className="layout mahasiswa-layout">
      <Header role="mahasiswa" />
      <div className="layout-body">
        <aside className="layout-sidebar">
          <Sidebar role="mahasiswa" />
        </aside>
        <main className="layout-content">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
