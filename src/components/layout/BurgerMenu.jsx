import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import {
  LayoutDashboard,
  Calendar,
  Bell,
  Package,
  Users,
  FileText,
  Settings,
  LogOut,
  X,
} from 'lucide-react';

export default function BurgerMenu({ open, onClose }) {
  const location = useLocation();

  const menuItems = [
    { path: '/pengurus', label: 'Dashboard', icon: LayoutDashboard },
    { path: '/pengurus/event', label: 'Event Organisasi', icon: Calendar },
    { path: '/pengurus/pengumuman', label: 'Pengumuman', icon: Bell },
    { path: '/pengurus/lost-found', label: 'Lost & Found', icon: Package },
    { path: '/pengurus/anggota', label: 'Anggota Organisasi', icon: Users },
    {
      path: '/pengurus/pendaftaran',
      label: 'Pendaftaran Anggota',
      icon: FileText,
    },
    { path: '/pengurus/proposal', label: 'Proposal & Arsip', icon: FileText },
    {
      path: '/pengurus/pengaturan',
      label: 'Pengaturan Organisasi',
      icon: Settings,
    },
  ];

  const isActive = (path) => location.pathname === path;

  return (
    <div
      className={`fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-white to-gray-50 shadow-2xl transform transition-transform duration-300 ${
        open ? 'translate-x-0' : '-translate-x-full'
      }`}
    >
      {/* Header */}
      <div className="p-6 border-b-2 border-gray-200">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="text-xl font-bold text-gray-900">Pengurus UFO</h2>
            <p className="text-xs text-gray-500 mt-1">Dashboard Internal</p>
          </div>
          <button
            onClick={onClose}
            aria-label="close"
            className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <X size={20} className="text-gray-600" />
          </button>
        </div>
      </div>

      {/* Navigation */}
      <nav className="p-4 space-y-2 flex-1 overflow-y-auto">
        {menuItems.map((item) => {
          const Icon = item.icon;
          const active = isActive(item.path);
          return (
            <Link
              key={item.path}
              to={item.path}
              onClick={onClose}
              className={`flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 ${
                active
                  ? 'bg-purple-100 text-purple-700 font-semibold border-2 border-purple-300'
                  : 'text-gray-700 hover:bg-gray-100 border-2 border-transparent'
              }`}
            >
              <Icon size={20} />
              <span>{item.label}</span>
            </Link>
          );
        })}
      </nav>

      {/* Footer */}
      <div className="p-4 border-t-2 border-gray-200">
        <button className="flex items-center gap-3 w-full px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition-colors border-2 border-red-200">
          <LogOut size={20} />
          <span className="font-semibold">Logout</span>
        </button>
      </div>
    </div>
  );
}
