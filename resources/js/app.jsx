import React from "react";
import ReactDOM from "react-dom/client";
import '../css/app.css';

function App() {
    return (
        <div className="min-h-screen bg-gray-100 flex items-center justify-center">
            <h1 className="text-4xl font-bold text-blue-600">
                Mentora 🚀 Tailwind aktif!
            </h1>
        </div>
    );
}

ReactDOM.createRoot(document.getElementById("app")).render(<App />);