<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Venue Management</title>
  <style>
    /*Dark Background*/
    body {
      margin: 0; padding: 0;
      font-family: "Segoe UI", Roboto, sans-serif;
      background-color: #0d0d0d;
      color: #e8e6e3;
    }
    header {
      background: linear-gradient(90deg, #111111, #1a1a1a);
      padding: 1rem 2rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.7);
    }
    header nav a {
      color: #80cbc4; text-decoration: none; font-size:1.2rem; font-weight: bold;
    }
    main { padding: 2rem; }

    /* ==== Section Title ==== */
    h1 {
      margin-top: 0;
      color: #ffffff;
      border-bottom: 2px solid #80cbc4;
      padding-bottom: 0.5rem;
      margin-bottom: 1.5rem;
    }

    /* ==== Card Wrapper ==== */
    .card {
      background: #1a1a1a;
      padding: 1.5rem;
      border-radius: 6px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.8);
      margin-bottom: 2rem;
    }

    /* ==== Buttons ==== */
    a.button, .button {
      display: inline-block;
      background: #80cbc4; color: #0d0d0d;
      padding: 0.5rem 1rem; border-radius: 4px;
      text-decoration: none; font-weight: 600;
      box-shadow: 0 2px 6px rgba(0,0,0,0.5);
      transition: background .2s;
    }
    a.button:hover, .button:hover { background: #4f9a8e; }

    /* ==== Tables ==== */
    table {
      width: 100%; border-collapse: collapse;
      background: transparent;
      margin-top: 1rem;
    }
    table thead { background: #222; }
    table th, table td {
      padding: 0.75rem 1rem; text-align: left;
      border-bottom: 1px solid #333;
    }
    table th {
      color: #c0c0c0; font-weight: 600;
    }
    table tbody tr:nth-child(odd)  { background: #1e1e1e; }
    table tbody tr:nth-child(even) { background: #141414; }

    /* ==== Action Buttons ==== */
    .actions {
      display: flex; gap: 0.5rem;
    }
    .actions a {
      background: #3949ab; color: #fff;
      padding: 0.4rem 0.8rem; border-radius: 4px;
      text-decoration: none; font-size: .9rem;
      transition: background .2s;
    }
    .actions a:hover { background: #2e358f; }
    .actions button {
      background: #e91e63; color: #fff;
      border: none; padding: 0.4rem 0.8rem;
      border-radius: 4px; font-size: .9rem;
      cursor: pointer; transition: background .2s;
    }
    .actions button:hover { background: #c2185b; }

    /* ==== Forms ==== */
    form label {
      display: block; margin-bottom: .3rem; color: #ccc;
      margin-top: 1rem;
    }
    form input[type="text"],
    form input[type="number"],
    form textarea {
      width: 100%; padding: .5rem; margin-bottom: .5rem;
      background: #111; border: 1px solid #333; border-radius: 4px;
      color: #e8e6e3; box-sizing: border-box;
    }
    form textarea { min-height: 100px; resize: vertical; }

    /* ==== Alerts ==== */
    .alert-success {
      background: #2e3b32; color: #a6cca6;
      padding: .75rem 1rem; border-left: 4px solid #80cbc4;
      border-radius: 4px; margin-bottom: 1rem;
    }

  </style>

  <main>
    @yield('content')
  </main>
</body>
</html>
