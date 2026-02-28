import React from 'react';

/**
 * Badge Component - Untuk menampilkan status, kategori, label
 *
 * Props:
 * - children: ReactNode
 * - variant: 'default' | 'success' | 'danger' | 'warning' | 'info' | 'purple'
 * - size?: 'sm' | 'md' | 'lg'
 * - className?: string
 */
export default function Badge({
  children,
  variant = 'default',
  size = 'md',
  className = '',
}) {
  const baseClass = 'inline-flex items-center font-semibold rounded-full';

  const variantClasses =
    {
      default: 'bg-gray-200 text-gray-800',
      success: 'bg-green-100 text-green-800',
      danger: 'bg-red-100 text-red-800',
      warning: 'bg-yellow-100 text-yellow-800',
      info: 'bg-blue-100 text-blue-800',
      purple: 'bg-purple-100 text-purple-800',
    }[variant] || 'bg-gray-200 text-gray-800';

  const sizeClasses =
    {
      sm: 'px-2 py-1 text-xs',
      md: 'px-3 py-1.5 text-sm',
      lg: 'px-4 py-2 text-base',
    }[size] || 'px-3 py-1.5 text-sm';

  return (
    <span
      className={`${baseClass} ${variantClasses} ${sizeClasses} ${className}`}
    >
      {children}
    </span>
  );
}
