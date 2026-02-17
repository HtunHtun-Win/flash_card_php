<?php
session_start();
define('ROOT_PATH', dirname(__DIR__, 3));
require_once ROOT_PATH . '/app/controller/CardController.php';
require_once ROOT_PATH . '/app/views/layout/header.php';

$cardSetId = $_GET['id'] ?? 0;
$userId = $_SESSION['user']->id ?? 0;

$controller = new CardController();
$data = $controller->getCardSetWithCards($cardSetId, $userId);

if (!$data) {
    die("Card set not found or you are not authorized.");
}

$cardSet = $data['cardSet'];
$cards = $data['cards'];

$cardsForJs = [];
foreach ($cards as $card) {
    $opts = [];
    foreach ($card->options as $opt) {
        $opts[] = ['id' => $opt->id, 'text' => $opt->option_text];
    }
    $cardsForJs[] = [
        'id' => $card->id,
        'question' => $card->question,
        'options' => $opts,
        'answer' => $card->answer
    ];
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <?= htmlspecialchars($cardSet->name) ?>
                        </h4>

                        <button
                            class="btn btn-outline-secondary"
                            onclick="window.location.href='/app/action/CardAction.php'">
                            Back to Home
                        </button>
                    </div>
                    <p class="text-muted"><?= htmlspecialchars($cardSet->desc) ?></p>

                    <?php if (count($cardsForJs) === 0): ?>
                        <div class="alert alert-info">No cards to review.</div>
                    <?php else: ?>
                        <div id="reviewApp">
                            <div class="mb-3">
                                <div class="progress" style="height:12px;">
                                    <div id="progressBar" class="progress-bar bg-success" style="width:0%">0%</div>
                                </div>
                            </div>

                            <div id="cardArea" class="card fade show">
                                <div class="card-body">
                                    <h5 id="question" class="fw-semibold"></h5>
                                    <div id="options" class="list-group my-3"></div>
                                    <div id="feedback" class="mb-3"></div>

                                    <div class="d-flex justify-content-between">
                                        <!-- <button id="prevBtn" class="btn btn-outline-secondary" disabled>Previous</button> -->
                                        <div>
                                            <input type="hidden" id="prevBtn" disabled></input>
                                        </div>
                                        <div>
                                            <button id="checkBtn" class="btn btn-success">Check</button>
                                            <button id="nextBtn" class="btn btn-primary" disabled>Next</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="finishArea" class="mt-3" style="display:none;"></div>
                        </div>

                        <script>
                            const cards = <?= json_encode($cardsForJs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

                            let currentIndex = 0;
                            const totalCards = cards.length;

                            const answered = Array(totalCards).fill(null);
                            const correctMap = Array(totalCards).fill(false);

                            const questionEl = document.getElementById('question');
                            const optionsEl = document.getElementById('options');
                            const feedbackEl = document.getElementById('feedback');
                            const prevBtn = document.getElementById('prevBtn');
                            const nextBtn = document.getElementById('nextBtn');
                            const checkBtn = document.getElementById('checkBtn');
                            const progressBar = document.getElementById('progressBar');
                            const cardArea = document.getElementById('cardArea');
                            const finishArea = document.getElementById('finishArea');

                            // Store a randomized option order per card so order stays consistent when navigating
                            const shuffledOptions = Array(totalCards).fill(null);

                            function shuffleArray(arr) {
                                const a = arr.slice();
                                for (let i = a.length - 1; i > 0; i--) {
                                    const j = Math.floor(Math.random() * (i + 1));
                                    [a[i], a[j]] = [a[j], a[i]];
                                }
                                return a;
                            }

                            function updateProgress(i) {
                                const pct = Math.round(((i + 1) / totalCards) * 100);
                                progressBar.style.width = pct + '%';
                                progressBar.textContent = `${i + 1}/${totalCards}`;
                            }

                            function renderCard(i) {
                                const card = cards[i];

                                questionEl.textContent = "Question : " + card.question;
                                optionsEl.innerHTML = '';
                                feedbackEl.innerHTML = '';

                                // Determine options order for this card (persist randomized order per card)
                                let optionsToShow = shuffledOptions[i];
                                if (!optionsToShow) {
                                    optionsToShow = shuffleArray(card.options);
                                    shuffledOptions[i] = optionsToShow;
                                }

                                optionsToShow.forEach(opt => {
                                    const label = document.createElement('label');
                                    label.className = 'list-group-item list-group-item-action';

                                    const input = document.createElement('input');
                                    input.type = 'radio';
                                    input.name = 'option';
                                    input.value = opt.id;
                                    input.className = 'form-check-input me-2';

                                    label.prepend(input);
                                    label.append(opt.text);

                                    label.onclick = () => {
                                        document.querySelectorAll('#options .list-group-item')
                                            .forEach(l => l.classList.remove('active'));
                                        label.classList.add('active');
                                        input.checked = true;
                                    };

                                    optionsEl.appendChild(label);
                                });

                                if (answered[i] !== null) {
                                    const sel = document.querySelector(`input[value="${answered[i]}"]`);
                                    if (sel) {
                                        sel.checked = true;
                                        if (sel.parentElement) sel.parentElement.classList.add('active');
                                    }
                                    showFeedback(i, answered[i], true);
                                } else {
                                    nextBtn.disabled = true;
                                    checkBtn.disabled = false;
                                }

                                prevBtn.disabled = (i === 0);
                                updateProgress(i);
                            }

                            function showFeedback(index, selectedId, restoring = false) {
                                const card = cards[index];
                                const isCorrect = parseInt(selectedId) === parseInt(card.answer);
                                const correct = card.options.find(o => parseInt(o.id) === parseInt(card.answer));
                                const correctText = correct ? escapeHtml(correct.text) : 'N/A';

                                // Disable inputs
                                document.querySelectorAll('#options input').forEach(i => i.disabled = true);

                                if (!restoring) answered[index] = selectedId;

                                // Clear previous list styles
                                document.querySelectorAll('#options .list-group-item').forEach(li => {
                                    li.classList.remove('list-group-item-success', 'list-group-item-danger', 'active');
                                });

                                // Highlight selected and correct items
                                const selInput = document.querySelector(`#options input[value="${selectedId}"]`);
                                const correctInput = document.querySelector(`#options input[value="${card.answer}"]`);

                                if (isCorrect) {
                                    feedbackEl.innerHTML = `<div class="alert alert-success">Correct!</div>`;
                                    correctMap[index] = true;
                                    if (selInput && selInput.parentElement) selInput.parentElement.classList.add('list-group-item-success', 'active');
                                } else {
                                    correctMap[index] = false;
                                    feedbackEl.innerHTML = `<div class="alert alert-danger">Incorrect. Correct answer: <strong>${correctText}</strong></div>`;
                                    if (selInput && selInput.parentElement) selInput.parentElement.classList.add('list-group-item-danger', 'active');
                                    if (correctInput && correctInput.parentElement) correctInput.parentElement.classList.add('list-group-item-success');
                                }

                                nextBtn.textContent = (index === totalCards - 1) ? 'Finish' : 'Next';
                                nextBtn.disabled = false;
                                checkBtn.disabled = true;
                            }

                            function escapeHtml(str) {
                                return str.replace(/[&<>"']/g, m => ({
                                    '&': '&amp;',
                                    '<': '&lt;',
                                    '>': '&gt;',
                                    '"': '&quot;',
                                    "'": '&#039;'
                                })[m]);
                            }

                            checkBtn.onclick = () => {
                                const sel = document.querySelector('input[name=option]:checked');
                                if (!sel) return alert('Please select an option');
                                showFeedback(currentIndex, sel.value);
                            };

                            prevBtn.onclick = () => {
                                currentIndex--;
                                renderCard(currentIndex);
                            };

                            nextBtn.onclick = () => {
                                if (currentIndex === totalCards - 1) {
                                    const score = correctMap.filter(v => v).length;

                                    cardArea.style.display = 'none';
                                    finishArea.style.display = 'block';
                                    finishArea.innerHTML = `
            <div class="alert alert-info text-center fs-5">
                Result: <strong>${score} out of ${totalCards}</strong>
            </div>
            <div class="text-center">
                <button class="btn btn-primary" onclick="location.reload()">Review Again</button>
            </div>
        `;
                                    return;
                                }

                                currentIndex++;
                                renderCard(currentIndex);
                            };

                            renderCard(0);
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>