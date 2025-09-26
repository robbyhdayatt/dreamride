const pages = document.querySelectorAll('.page');
const ultimonContainer = document.getElementById('ultimon-container');
const heroContent = document.querySelector('#landingPage .hero-content');
const mainBody = document.body;
const userAnswers = {};
let currentPageIndex = 0;
const quizPagesCount = 6;
const progressBar = document.getElementById('progress-bar');
const progressContainer = document.getElementById('progress-container');

window.addEventListener('load', () => {
    const curtain = document.getElementById('curtain');
    setTimeout(() => {
        curtain.style.display = 'none';
    }, 2500);
    pages[0].classList.remove('hidden');

    heroContent.appendChild(ultimonContainer);
    ultimonContainer.classList.add('on-hero');
});

function actionWithAnimation(callback) {
    ultimonContainer.classList.add('confirm');
    setTimeout(() => {
        ultimonContainer.classList.remove('confirm');
        if (callback) callback();
    }, 600);
}

function updateProgress(pageIndex) {
    if (pageIndex > 0 && pageIndex <= quizPagesCount) {
        progressContainer.style.display = 'block';
        const progress = ((pageIndex - 1) / (quizPagesCount - 1)) * 100;
        progressBar.style.width = `${progress}%`;
    } else {
        progressContainer.style.display = 'none';
    }
}

function goToPage(pageIndex, callback) {
    if (pageIndex === currentPageIndex) return;

    const isComingFromHero = currentPageIndex === 0;
    const isGoingToHero = pageIndex === 0;

    if (isComingFromHero && !isGoingToHero) {
        mainBody.appendChild(ultimonContainer);
        ultimonContainer.classList.remove('on-hero');
    }

    const currentPage = pages[currentPageIndex];
    const nextPage = pages[pageIndex];

    currentPage.classList.add('exit-active');
    nextPage.classList.remove('hidden');
    nextPage.classList.add('enter');
    setTimeout(() => {
        nextPage.classList.add('enter-active');
        nextPage.classList.remove('enter');
    }, 100);

    setTimeout(() => {
        currentPage.classList.add('hidden');
        currentPage.classList.remove('exit-active');
        nextPage.classList.remove('enter-active', 'enter');

        if (isGoingToHero) {
            heroContent.appendChild(ultimonContainer);
            ultimonContainer.classList.add('on-hero');
        }

        currentPageIndex = pageIndex;
        updateProgress(currentPageIndex);
        if (callback) callback();
    }, 900);
}

function generateReasonText(answers) {
    const purposeMap = {
        'daily_practical': 'kebutuhan harian yang praktis',
        'family': 'aktivitas bersama keluarga',
        'hobby': 'teman touring dan hobi'
    };
    const priorityMap = {
        'efficiency': 'irit dan praktis',
        'style': `gaya ${answers.style_detail === 'classy_elegant' ? 'elegan' : 'trendy'}`,
        'performance': 'performa kencang',
        'comfort': 'kenyamanan superior'
    };

    let reason = `Berdasarkan pencarianmu untuk motor yang cocok untuk ${purposeMap[answers.main_purpose] || ''} dengan prioritas utama pada ${priorityMap[answers.main_priority] || ''}, motor ini adalah pilihan yang paling sesuai.`;

    return reason;
}

async function finishQuiz() {
    actionWithAnimation(() => {
        goToPage(7, () => {
            fetch('proses_prediksi.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(userAnswers)
            })
            .then(response => response.json())
            .then(result => {
                document.getElementById('resultName').textContent = userAnswers.name;
                document.getElementById('resultMotorImage').src = result.image;
                document.getElementById('resultMotorName').textContent = result.name;
                document.getElementById('resultMotorPrice').textContent = result.price;

                document.getElementById('resultReason').textContent = generateReasonText(userAnswers);

                const fullQuery = "PT Lautan Teduh Interniaga, Jl. Ikan Tenggiri, Pesawahan, Telukbetung Selatan, Bandar Lampung";
                const googleMapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(fullQuery)}`;
                document.getElementById('findDealerButton').href = googleMapsUrl;

                const specsContainer = document.getElementById('resultMotorSpecs');
                specsContainer.innerHTML = '';
                result.specs.forEach(spec => {
                    const specDiv = document.createElement('div');
                    specDiv.className = 'spec-item';
                    specDiv.innerHTML = `<strong>${spec.label}</strong> ${spec.value}`;
                    specsContainer.appendChild(specDiv);
                });

                setTimeout(() => { goToPage(8); }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                showCustomAlert('Oops, terjadi kesalahan saat mengambil data motor.');
                goToPage(0);
            });
        });
    });
}

document.getElementById('startButton').addEventListener('click', () => actionWithAnimation(() => goToPage(1)));
document.getElementById('nextButton1').addEventListener('click', () => {
    const name = document.getElementById('nameInput').value.trim();
    if (name === '') { showCustomAlert('Nama tidak boleh kosong dong :)'); return; }
    userAnswers.name = name;
    document.getElementById('ageQuestion').textContent = `Berapa usiamu, ${name}?`;
    actionWithAnimation(() => goToPage(2));
});
document.getElementById('ageSlider').addEventListener('input', (e) => document.getElementById('ageDisplay').textContent = e.target.value);
document.getElementById('nextButton2').addEventListener('click', () => {
    userAnswers.age = document.getElementById('ageSlider').value;
    actionWithAnimation(() => goToPage(3));
});
document.querySelectorAll('.options-container').forEach(container => {
    container.addEventListener('click', (e) => {
        const card = e.target.closest('.option-card');
        if (card) {
            actionWithAnimation(() => {
                const pageId = card.closest('.page').id; const value = card.dataset.value;
                if (pageId === 'quizPage3') { userAnswers.income_level = value; goToPage(4); }
                else if (pageId === 'quizPage4') { userAnswers.main_purpose = value; goToPage(5); }
                else if (pageId === 'quizPage5') { 
                    userAnswers.main_priority = value; 
                    if (value === 'style') { 
                        goToPage(6); 
                    } else { 
                        userAnswers.style_detail = '';
                        finishQuiz(); 
                    } 
                } 
                else if (pageId === 'quizPage6') { userAnswers.style_detail = value; finishQuiz(); }
            });
        }
    });
});

document.querySelectorAll('.back-button').forEach(button => {
    button.addEventListener('click', () => {
        const targetPage = parseInt(button.getAttribute('data-target'), 10);
        goToPage(targetPage);
    });
});

document.getElementById('restartButton').addEventListener('click', () => goToPage(0));
document.querySelectorAll('.action-button, .option-card').forEach(el => {
    const ultimonImg = document.getElementById('ultimon-img');
    if (ultimonImg) {
        el.addEventListener('mouseover', () => ultimonImg.classList.add('excited'));
        el.addEventListener('mouseout', () => ultimonImg.classList.remove('excited'));
    }
});
document.addEventListener('mousemove', function(e) {
    const parallaxElements = document.querySelectorAll('[data-depth]');
    const centerX = window.innerWidth / 2;
    const centerY = window.innerHeight / 2;
    parallaxElements.forEach(el => {
        const depth = el.getAttribute('data-depth');
        const moveX = (e.clientX - centerX) * depth / 20;
        const moveY = (e.clientY - centerY) * depth / 20;
        el.style.transform = `translateX(${moveX}px) translateY(${moveY}px)`;
    });
});
function showCustomAlert(message) {
    const alertBox = document.getElementById('customAlert');
    alertBox.textContent = message;
    alertBox.classList.add('show');
    setTimeout(() => { alertBox.classList.remove('show'); }, 3000);
}