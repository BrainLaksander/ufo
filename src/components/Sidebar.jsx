import React from "react";
import { NavLink } from "react-router-dom";
export default function Sidebar({ items = [] }) {
    return (
        <nav className="sidebar">
            <ul>
                {items.map((i) => (
                    <li key={i.to}>
                        <NavLink
                            to={i.to}
                            className={({ isActive }) =>
                                isActive ? "active" : ""
                            }
                        >
                            {i.label}
                        </NavLink>
                    </li>
                ))}
            </ul>
        </nav>
    );
}
