import React from "react";
import { Navigate } from "react-router-dom";
import { useAuth } from "./useAuth";

export default function ProtectedRoute({ allowedRoles = [], children }) {
    const { user, loading } = useAuth();

    if (loading) return <div>Loading...</div>;

    if (!user) {
        // not authenticated
        return <Navigate to="/login" replace />;
    }

    if (allowedRoles.length && !allowedRoles.includes(user.role)) {
        // role not allowed
        return <Navigate to="/unauthorized" replace />;
    }

    return children;
}
