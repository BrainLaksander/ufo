import React from 'react';

/**
 * FilterChips Component - Menampilkan chip filter yang bisa dipilih
 *
 * Props:
 * - items: array of { id: string, label: string }
 * - selected: string (id dari item yang dipilih)
 * - onSelect: function(id)
 * - className?: string
 */
export default function FilterChips({
  items,
  selected,
  onSelect,
  className = '',
}) {
  return (
    <div className={`flex flex-wrap gap-2 ${className}`}>
      {items.map((item) => (
        <button
          key={item.id}
          onClick={() => onSelect(item.id)}
          className={`px-4 py-2 rounded-full font-medium transition-all ${
            selected === item.id
              ? 'bg-purple-700 text-white'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
          }`}
        >
          {item.label}
        </button>
      ))}
    </div>
  );
}
