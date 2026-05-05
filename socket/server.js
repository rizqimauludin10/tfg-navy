const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const app = express();
const server = http.createServer(app);

app.use(cors());
app.use(express.json());

const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
  },
});

// Simpan daftar user online
let onlineUsers = {};

io.on("connection", (socket) => {
  console.log(`[+] Connected: ${socket.id}`);

  // ── User join ──
  socket.on("user_join", (data) => {
    onlineUsers[socket.id] = {
      user_id: data.user_id,
      name: data.name,
      role: data.role,
    };
    console.log(`[+] Join: ${data.name} (${data.role})`);

    // Broadcast jumlah user online ke semua
    io.emit("online_count", Object.keys(onlineUsers).length);
  });

  // ── Unit dipindahkan ──
  socket.on("unit_moved", (data) => {
    // Broadcast ke semua client KECUALI pengirim
    socket.broadcast.emit("unit_moved", {
      unit_id: data.unit_id,
      name: data.name,
      type_name: data.type_name,
      pos_x: data.pos_x,
      pos_y: data.pos_y,
      moved_by: data.moved_by,
      timestamp: data.timestamp,
    });
    console.log(
      `[~] Unit moved: ${data.name} → x:${data.pos_x} y:${data.pos_y}`,
    );
  });

  // ── Unit ditambah ──
  socket.on("unit_added", (data) => {
    socket.broadcast.emit("unit_added", data);
    console.log(`[+] Unit added: ${data.name}`);
  });

  // ── Unit dihapus ──
  socket.on("unit_deleted", (data) => {
    socket.broadcast.emit("unit_deleted", { unit_id: data.unit_id });
    console.log(`[-] Unit deleted: id ${data.unit_id}`);
  });

  // ── Unit diedit ──
  socket.on("unit_edited", (data) => {
    socket.broadcast.emit("unit_edited", {
      unit_id: data.unit_id,
      name: data.name,
    });
    console.log(`[e] Unit edited: id ${data.unit_id}`);
  });

  // ── Disconnect ──
  socket.on("disconnect", () => {
    const user = onlineUsers[socket.id];
    if (user) console.log(`[-] Disconnect: ${user.name}`);
    delete onlineUsers[socket.id];
    io.emit("online_count", Object.keys(onlineUsers).length);
  });
});

// Health check endpoint
app.get("/status", (req, res) => {
  res.json({
    status: "ok",
    online: Object.keys(onlineUsers).length,
    users: Object.values(onlineUsers),
  });
});

const PORT = 3000;
server.listen(PORT, () => {
  console.log(`✅ Socket.io server running on port ${PORT}`);
});
