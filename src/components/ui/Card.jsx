import React from 'react';

/**
 * Card Component - Reusable card dengan border dan styling konsisten
 *
 * Props:
 * - children: ReactNode
 * - className?: string (additional classes)
 * - border?: boolean (default: true) - border-2
 * - rounded?: 'lg' | 'xl' | '2xl' (default: '2xl')
 * - onClick?: function
 * - hover?: boolean - tambah hover effect
 */
export default function Card({
  children,
  className = '',
  border = true,
  rounded = '2xl',
  onClick,
  hover = false,
  variant = 'default', // 'default' | 'highlight'
}) {
  const roundedClass =
    {
      lg: 'rounded-lg',
      xl: 'rounded-xl',
      '2xl': 'rounded-2xl',
    }[rounded] || 'rounded-2xl';

  const borderClass = border ? 'border-2 border-gray-200' : '';
  const hoverClass = hover
    ? 'hover:shadow-lg hover:border-purple-400 transition-all cursor-pointer'
    : '';
  const variantClass = variant === 'highlight' ? 'bg-purple-50' : 'bg-white';

  return (
    <div
      className={`${variantClass} ${roundedClass} ${borderClass} ${hoverClass} p-4 sm:p-6 ${className}`}
      onClick={onClick}
    >
      {children}
    </div>
  );
}
