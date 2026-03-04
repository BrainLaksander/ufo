import React from 'react';

/**
 * Tabs Component - Navigasi tab dengan indikator active
 *
 * Props:
 * - tabs: array of { id: string, label: string }
 * - activeTab: string (id dari tab yang aktif)
 * - onTabChange: function(tabId)
 * - className?: string
 */
export default function Tabs({ tabs, activeTab, onTabChange, className = '' }) {
  return (
    <div
      className={`flex gap-2 border-b-2 border-gray-200 overflow-x-auto pb-0 ${className}`}
    >
      {tabs.map((tab) => (
        <button
          key={tab.id}
          onClick={() => onTabChange(tab.id)}
          className={`px-4 py-3 font-semibold whitespace-nowrap transition-all border-b-2 -mb-[2px] ${
            activeTab === tab.id
              ? 'text-purple-700 border-purple-700'
              : 'text-gray-600 border-transparent hover:text-gray-900'
          }`}
        >
          {tab.label}
        </button>
      ))}
    </div>
  );
}
