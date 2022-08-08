import React from "react";
import { Redirect, Route } from "react-router-dom";
import { useSelector } from "react-redux";

export function handleSessionEnd() {
  window.location.assign("http://localhost:4000/login");
}

export function ProtectedRoute({ component: Component, ...restOfProps }) {
  const state = useSelector((state) => state);
  const isAuthenticated = state.auth.isAuthenticated;

  return (
    <Route
      {...restOfProps}
      render={(props) =>
        isAuthenticated ? <Component {...props} /> : <Redirect to="/login" />
      }
    />
  );
}
