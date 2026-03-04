import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from './useAuth';

export default function ProtectedRoute({ allowedRoles = [], children }) {
  const { user, loading } = useAuth();

  if (loading) return <div>Loading...</div>;

  if (!user) {
    // not authenticated -> redirect to canonical server login page
    if (typeof window !== 'undefined') {
      window.location.href = '/portal/login';
      return null;
    }
    return <Navigate to="/login" replace />;
  }

  if (allowedRoles.length && !allowedRoles.includes(user.role)) {
    // role not allowed
    return <Navigate to="/unauthorized" replace />;
  }

  return children;
}
