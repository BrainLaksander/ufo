import React, { useEffect } from 'react';

/**
 * Dialog/Modal Component - Menampilkan modal dialog dengan backdrop
 *
 * Props:
 * - open: boolean - kontrol apakah dialog terbuka
 * - onClose: function - callback saat menutup dialog
 * - title?: string
 * - children: ReactNode
 * - size?: 'sm' | 'md' | 'lg' | 'xl' (default: 'md')
 */
export default function Dialog({
  open,
  onClose,
  title,
  children,
  size = 'md',
}) {
  useEffect(() => {
    if (open) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
    return () => {
      document.body.style.overflow = 'unset';
    };
  }, [open]);

  if (!open) return null;

  const sizeClasses =
    {
      sm: 'max-w-sm',
      md: 'max-w-md',
      lg: 'max-w-lg',
      xl: 'max-w-xl',
    }[size] || 'max-w-md';

  const handleBackdropClick = (e) => {
    if (e.target === e.currentTarget) {
      onClose();
    }
  };

  return (
    <div
      className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
      onClick={handleBackdropClick}
    >
      <div
        className={`bg-white rounded-2xl shadow-xl ${sizeClasses} w-full max-h-[90vh] overflow-y-auto`}
      >
        {/* Header */}
        {title && (
          <div className="border-b border-gray-200 px-6 py-4 flex justify-between items-center sticky top-0 bg-white">
            <h2 className="text-xl font-bold text-gray-900">{title}</h2>
            <button
              onClick={onClose}
              className="text-gray-400 hover:text-gray-600 transition-colors"
              aria-label="Tutup dialog"
            >
              <svg
                className="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        )}

        {/* Content */}
        <div className="p-6">{children}</div>
      </div>
    </div>
  );
}
