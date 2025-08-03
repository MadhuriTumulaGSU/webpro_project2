// fifteen.js

let PUZZLE_SIZE = 4;
const TILE_SIZE = 100;
let EMPTY = 0;
let tiles = [];
let emptyRow, emptyCol;
let moves = 0;
let timer = 0;
let timerInterval = null;
let bestTime = null;
let bestMoves = null;
let backgroundUrl = "backgrounds/bg1.jpg";
let music = document.getElementById("bg-music");

function setPuzzleSize(size) {
  PUZZLE_SIZE = size;
  EMPTY = 0;
  document.getElementById("puzzle-container").style.width =
    size * TILE_SIZE + 4 + "px";
  document.getElementById("puzzle-container").style.height =
    size * TILE_SIZE + 4 + "px";
  document.getElementById(
    "puzzle-container"
  ).style.gridTemplateColumns = `repeat(${size}, 1fr)`;
  document.getElementById(
    "puzzle-container"
  ).style.gridTemplateRows = `repeat(${size}, 1fr)`;
}

function initPuzzle() {
  setPuzzleSize(PUZZLE_SIZE);
  const container = document.getElementById("puzzle-container");
  container.innerHTML = "";
  tiles = [];
  let num = 1;
  for (let row = 0; row < PUZZLE_SIZE; row++) {
    tiles[row] = [];
    for (let col = 0; col < PUZZLE_SIZE; col++) {
      if (row === PUZZLE_SIZE - 1 && col === PUZZLE_SIZE - 1) {
        tiles[row][col] = EMPTY;
        continue;
      }
      tiles[row][col] = num;
      const tile = document.createElement("div");
      tile.className = "puzzle-tile";
      tile.id = `tile-${row}-${col}`;
      tile.style.backgroundImage = `url('${backgroundUrl}')`;
      tile.style.backgroundSize = `${PUZZLE_SIZE * TILE_SIZE}px ${
        PUZZLE_SIZE * TILE_SIZE
      }px`;
      tile.style.backgroundPosition = `-${col * TILE_SIZE}px -${
        row * TILE_SIZE
      }px`;
      tile.textContent = num;
      tile.style.gridColumn = col + 1;
      tile.style.gridRow = row + 1;
      tile.addEventListener("click", () => tileClick(row, col));
      tile.addEventListener("mouseover", () =>
        highlightMovable(row, col, true)
      );
      tile.addEventListener("mouseout", () =>
        highlightMovable(row, col, false)
      );
      container.appendChild(tile);
      num++;
    }
  }
  emptyRow = PUZZLE_SIZE - 1;
  emptyCol = PUZZLE_SIZE - 1;
  moves = 0;
  timer = 0;
  updateInfo();
  clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    timer++;
    updateInfo();
  }, 1000);
  document.getElementById("win-notification").style.display = "none";
  if (music) music.play();
}

function updateInfo() {
  document.getElementById("timer").textContent = `Time: ${timer}s`;
  document.getElementById("moves").textContent = `Moves: ${moves}`;
  if (bestTime !== null && bestMoves !== null) {
    document.getElementById(
      "best-score"
    ).textContent = `| Best: ${bestTime}s, ${bestMoves} moves`;
  }
}

function isMovable(row, col) {
  // Allow sliding multiple tiles in row/col
  return (
    (row === emptyRow || col === emptyCol) &&
    !(row === emptyRow && col === emptyCol)
  );
}

function highlightMovable(row, col, on) {
  if (isMovable(row, col)) {
    let min = Math.min(row, emptyRow),
      max = Math.max(row, emptyRow);
    if (row === emptyRow) {
      for (let c = Math.min(col, emptyCol); c <= Math.max(col, emptyCol); c++) {
        let tile = document.getElementById(`tile-${row}-${c}`);
        if (tile) tile.classList.toggle("movablepiece", on);
      }
    } else if (col === emptyCol) {
      for (let r = Math.min(row, emptyRow); r <= Math.max(row, emptyRow); r++) {
        let tile = document.getElementById(`tile-${r}-${col}`);
        if (tile) tile.classList.toggle("movablepiece", on);
      }
    }
  }
}

function tileClick(row, col) {
  if (!isMovable(row, col)) return;
  moves++;
  if (row === emptyRow) {
    let dir = col < emptyCol ? 1 : -1;
    for (let c = emptyCol - dir; c !== col - dir; c -= dir) {
      moveTile(row, c, row, c + dir);
    }
  } else if (col === emptyCol) {
    let dir = row < emptyRow ? 1 : -1;
    for (let r = emptyRow - dir; r !== row - dir; r -= dir) {
      moveTile(r, col, r + dir, col);
    }
  }
  updateInfo();
  if (isSolved()) {
    clearInterval(timerInterval);
    if (
      bestTime === null ||
      timer < bestTime ||
      (timer === bestTime && moves < bestMoves)
    ) {
      bestTime = timer;
      bestMoves = moves;
    }
    document.getElementById("win-notification").style.display = "block";
    if (music) music.pause();
  }
}

function moveTile(fromRow, fromCol, toRow, toCol) {
  tiles[toRow][toCol] = tiles[fromRow][fromCol];
  tiles[fromRow][fromCol] = EMPTY;
  let tile = document.getElementById(`tile-${fromRow}-${fromCol}`);
  if (tile) {
    tile.id = `tile-${toRow}-${toCol}`;
    tile.style.gridColumn = toCol + 1;
    tile.style.gridRow = toRow + 1;
  }
  emptyRow = fromRow;
  emptyCol = fromCol;
}

function isSolved() {
  let num = 1;
  for (let row = 0; row < PUZZLE_SIZE; row++) {
    for (let col = 0; col < PUZZLE_SIZE; col++) {
      if (row === PUZZLE_SIZE - 1 && col === PUZZLE_SIZE - 1) {
        if (tiles[row][col] !== EMPTY) return false;
      } else {
        if (tiles[row][col] !== num) return false;
        num++;
      }
    }
  }
  return true;
}

function shufflePuzzle() {
  let movesToShuffle = PUZZLE_SIZE * PUZZLE_SIZE * 20;
  for (let i = 0; i < movesToShuffle; i++) {
    let neighbors = [];
    for (let d = 0; d < PUZZLE_SIZE; d++) {
      if (d !== emptyCol) neighbors.push([emptyRow, d]);
      if (d !== emptyRow) neighbors.push([d, emptyCol]);
    }
    let [row, col] = neighbors[Math.floor(Math.random() * neighbors.length)];
    if (isMovable(row, col)) {
      if (row === emptyRow) {
        let dir = col < emptyCol ? 1 : -1;
        for (let c = emptyCol - dir; c !== col - dir; c -= dir) {
          moveTile(row, c, row, c + dir);
        }
      } else if (col === emptyCol) {
        let dir = row < emptyRow ? 1 : -1;
        for (let r = emptyRow - dir; r !== row - dir; r -= dir) {
          moveTile(r, col, r + dir, col);
        }
      }
    }
  }
  moves = 0;
  timer = 0;
  updateInfo();
  document.getElementById("win-notification").style.display = "none";
  clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    timer++;
    updateInfo();
  }, 1000);
  if (music) music.play();
}

function cheatPuzzle() {
  initPuzzle();
  clearInterval(timerInterval);
  timerInterval = null;
  document.getElementById("win-notification").style.display = "block";
  if (music) music.pause();
}

document.getElementById("shuffle-button").onclick = shufflePuzzle;
document.getElementById("cheat-button").onclick = cheatPuzzle;
document.getElementById("background-select").onchange = function () {
  backgroundUrl = this.value;
  initPuzzle();
};
document.getElementById("size-select").onchange = function () {
  PUZZLE_SIZE = parseInt(this.value);
  initPuzzle();
};

window.onload = function () {
  backgroundUrl = document.getElementById("background-select").value;
  PUZZLE_SIZE = parseInt(document.getElementById("size-select").value);
  initPuzzle();
};
