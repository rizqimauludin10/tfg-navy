const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");

const app = express();
const server = http.createServer(app);

app.use(cors());
app.use(express.json());

const io = new Server(server, {
  cors: { origin: "*", methods: ["GET", "POST"] },
});

let onlineUsers = {};

io.on("connection", (socket) => {
  console.log(`[+] Connected: ${socket.id}`);

  socket.on("user_join", (data) => {
    onlineUsers[socket.id] = {
      user_id: data.user_id,
      name: data.name,
      role: data.role,
    };
    io.emit("online_count", Object.keys(onlineUsers).length);
  });

  // Satu event untuk semua pergerakan
  socket.on("unit_moved", (data) => {
    socket.broadcast.emit("unit_moved", data);
  });

  socket.on("unit_added", (data) => {
    socket.broadcast.emit("unit_added", data);
  });

  socket.on("unit_deleted", (data) => {
    io.emit("unit_deleted", { unit_id: data.unit_id });
  });

  socket.on("unit_edited", (data) => {
    socket.broadcast.emit("unit_edited", data);
  });

  socket.on("start_point_set", (data) => {
    socket.broadcast.emit("start_point_set", data);
  });

  socket.on("disconnect", () => {
    delete onlineUsers[socket.id];
    io.emit("online_count", Object.keys(onlineUsers).length);
  });
});

server.listen(3000, () => {
  console.log("Socket.io server running on port 3000");
});
