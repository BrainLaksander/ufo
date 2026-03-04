import React from 'react';

export default function Spinner({ size = 40 }) {
  return (
    <div className="flex items-center justify-center" aria-hidden="true">
      <svg
        className="animate-spin text-[#3B1C57]"
        width={size}
        height={size}
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <circle
          cx="12"
          cy="12"
          r="10"
          stroke="#E5E7EB"
          strokeWidth="4"
          className="opacity-40"
        />
        <path
          d="M22 12a10 10 0 00-10-10"
          stroke="currentColor"
          strokeWidth="4"
          strokeLinecap="round"
        />
      </svg>
    </div>
  );
}
