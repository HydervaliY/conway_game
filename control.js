let intervalId;
function startGame() {
  if (!running) {
    running = true;
    intervalId = setInterval(() => {
      nextGeneration();
    }, 300);
  }
}
function stopGame() {
  running = false;
  clearInterval(intervalId);
}
function nextGeneration() {
  nextGrid = JSON.parse(JSON.stringify(grid));
  for (let r = 0; r < rows; r++) {
    for (let c = 0; c < cols; c++) {
      let neighbors = countNeighbors(r, c);
      if (grid[r][c] === 1 && (neighbors < 2 || neighbors > 3)) nextGrid[r][c] = 0;
      else if (grid[r][c] === 0 && neighbors === 3) nextGrid[r][c] = 1;
    }
  }
  grid = nextGrid;
  generation++;
  renderGrid();
  updateStats();
}
function next23Generations() {
  for (let i = 0; i < 23; i++) {
    nextGeneration();
  }
}
function resetGame() {
  stopGame();
  generation = 0;
  createGrid();
}
function countNeighbors(r, c) {
  let count = 0;
  for (let dr = -1; dr <= 1; dr++) {
    for (let dc = -1; dc <= 1; dc++) {
      if (dr === 0 && dc === 0) continue;
      let nr = r + dr, nc = c + dc;
      if (nr >= 0 && nr < rows && nc >= 0 && nc < cols) {
        count += grid[nr][nc];
      }
    }
  }
  return count;
}