<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: php/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>


  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conway's Game of Life</title>
  <link rel="stylesheet" href="styles.css">

  <!-- 🔽 Add these lines here -->
  <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
  <script defer src="reactGrid.js"></script>
  <!-- ✅ This file will contain the React component -->
  
  <script defer src="script.js"></script>
  <script defer src="patterns/patterns.js"></script>

  <!-- <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Conway's Game of Life</title>
  <link rel="stylesheet" href="styles.css"> -->
  <style>
    #grid-container {
      display: grid;
      grid-template-columns: repeat(50, 15px);
      grid-template-rows: repeat(30, 15px);
      gap: 1px;
      margin: 20px auto;
      width: fit-content;
    }
    .cell {
      width: 15px;
      height: 15px;
      background-color: white;
      border: 1px solid #ddd;
    }
    .alive {
      background-color: blue;
    }
  </style>
</head>
<body>
  <header>
    <h1>Conway's Game of Life</h1>
    <p>Generation: <span id="generation">0</span> | Population: <span id="population">0</span></p>
  </header>

  <section id="controls">
    <button onclick="startGame()">▶️ Start</button>
    <button onclick="stopGame()">⏸ Stop</button>
    <button onclick="nextGen()">⏭ Next Gen</button>
    <button onclick="next23()">🔁 +23 Gens</button>
    <button onclick="resetGame()">♻️ Reset</button>
    <select id="patternSelect" onchange="loadPattern(this.value)">
      <option value="">🧬 Load Pattern</option>
      <option value="block">Still Life - Block</option>
      <option value="boat">Still Life - Boat</option>
      <option value="beehive">Still Life - Beehive</option>
      <option value="blinker">Oscillator - Blinker</option>
      <option value="beacon">Oscillator - Beacon</option>
      <option value="glider">Glider</option>
      <option value="gosper">Gosper Glider Gun</option>
    </select>
  </section>

  <section id="grid-container"></section>

  <footer>
    <p>Logged in as: <?php echo $_SESSION['email']; ?></p>
    <a href="php/logout.php">Logout</a>
  </footer>

  <script>
    const rows = 30;
    const cols = 50;
    let grid = [];
    let interval = null;
    let generation = 0;

    const container = document.getElementById('grid-container');
    const genDisplay = document.getElementById('generation');
    const popDisplay = document.getElementById('population');

    function createGrid() {
      grid = [];
      container.innerHTML = '';
      for (let r = 0; r < rows; r++) {
        let row = [];
        for (let c = 0; c < cols; c++) {
          const cell = document.createElement('div');
          cell.className = 'cell';
          cell.dataset.row = r;
          cell.dataset.col = c;
          cell.onclick = () => toggleCell(r, c);
          container.appendChild(cell);
          row.push(0);
        }
        grid.push(row);
      }
    }

    function toggleCell(r, c) {
      const index = r * cols + c;
      const cellDiv = container.children[index];
      grid[r][c] = 1 - grid[r][c];
      cellDiv.classList.toggle('alive', grid[r][c] === 1);
    }

    function getLiveNeighbors(r, c) {
      let count = 0;
      for (let dr = -1; dr <= 1; dr++) {
        for (let dc = -1; dc <= 1; dc++) {
          if (dr === 0 && dc === 0) continue;
          const nr = (r + dr + rows) % rows;
          const nc = (c + dc + cols) % cols;
          count += grid[nr][nc];
        }
      }
      return count;
    }

    function nextGeneration() {
      const newGrid = grid.map(arr => [...arr]);
      let population = 0;
      for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
          const neighbors = getLiveNeighbors(r, c);
          if (grid[r][c] === 1) {
            newGrid[r][c] = neighbors === 2 || neighbors === 3 ? 1 : 0;
          } else {
            newGrid[r][c] = neighbors === 3 ? 1 : 0;
          }
          population += newGrid[r][c];
        }
      }
      grid = newGrid;
      generation++;
      updateGrid();
      genDisplay.textContent = generation;
      popDisplay.textContent = population;
    }

    function updateGrid() {
      for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
          const index = r * cols + c;
          container.children[index].classList.toggle('alive', grid[r][c] === 1);
        }
      }
    }

    function startGame() {
      if (!interval) interval = setInterval(nextGeneration, 200);
    }

    function stopGame() {
      clearInterval(interval);
      interval = null;
    }

    function nextGen() {
      nextGeneration();
    }

    function next23() {
      for (let i = 0; i < 23; i++) {
        nextGeneration();
      }
    }

    function resetGame() {
      stopGame();
      createGrid();
      generation = 0;
      genDisplay.textContent = 0;
      popDisplay.textContent = 0;
    }

    function loadPattern(name) {
      resetGame();
      const midRow = Math.floor(rows / 2);
      const midCol = Math.floor(cols / 2);
      const patterns = {
        block: [[0,0],[0,1],[1,0],[1,1]],
        boat: [[0,0],[0,1],[1,0],[1,2],[2,1]],
        beehive: [[0,1],[0,2],[1,0],[1,3],[2,1],[2,2]],
        blinker: [[1,0],[1,1],[1,2]],
        beacon: [[0,0],[0,1],[1,0],[1,1],[2,2],[2,3],[3,2],[3,3]],
        glider: [[0,1],[1,2],[2,0],[2,1],[2,2]],
        gosper: [[5,1],[5,2],[6,1],[6,2], [5,11],[6,11],[7,11],[4,12],[8,12],[3,13],[9,13],[3,14],[9,14],[6,15],[4,16],[8,16],[5,17],[6,17],[7,17],[6,18], [3,21],[4,21],[5,21],[3,22],[4,22],[5,22],[2,23],[6,23],[1,25],[2,25],[6,25],[7,25], [3,35],[4,35],[3,36],[4,36]]
      };
      const pattern = patterns[name];
      if (pattern) {
        for (const [dr, dc] of pattern) {
          const r = midRow + dr;
          const c = midCol + dc;
          grid[r][c] = 1;
        }
        updateGrid();
      }
    }

    createGrid();
  </script>
</body>
</html>