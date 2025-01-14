<?php

namespace App\Traits;

use PhpScience\TextRank\TextRankFacade;
use PhpScience\TextRank\Tool\StopWords\English;
use PhpSpellcheck\Spellchecker\Aspell;

trait Dataset
{
    private function detectQuestionType($text)
    {
        $patterns = [
            'why' => 'why?',
            'because' => 'why?',
            'how' => 'how?',
            'what is' => 'what?',
            'is what' => 'what?',
            'when' => 'what?',
            'where' => 'what?',
            'who' => 'what?',
            'which' => 'what?',
            'can' => 'how?',
            'could' => 'how?',
            'do' => 'how?',
            'does' => 'how?',
            'is' => 'how?',
            'are' => 'how?',
            'will' => 'how?',
            'should' => 'how?',
            'would' => 'how?',
            'may' => 'how?',
        ];

        $types = [];
        foreach ($patterns as $keyword => $type) {
            if (str_contains(strtolower($text), $keyword)) {
                $types[] = $type;
            }
        }

        if (empty($types)) {
            return ['what?'];
        }

        return $types;
    }

    private function compeleteQuestion($question, $field)
    {
        if (str_contains($question, "why")) {
            return "why {$field} ?";
        } elseif (str_contains($question, "how")) {
            return "how to use  {$field} ?";
        }

        return "what is the {$field} ?";
    }

    private function extractKeywords($text, $stopWords)
    {
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $text);

        $words = explode(' ', $text);

        $filteredWords = array_filter($words, function ($word) use ($stopWords) {
            return !in_array($word, $stopWords) && strlen($word) > 2;
        });

        $wordFrequency = array_count_values($filteredWords);

        arsort($wordFrequency);

        $api = new TextRankFacade();

        $api->setStopWords(new English());

        return array_unique([
            ...array_keys($api->getOnlyKeyWords($text)),
            ...array_slice(array_keys($wordFrequency), 0, 5)
        ]);
    }

    private function stopWords()
    {
        return [
            ...$this->stopWordsEnglish(),
            ...$this->stopWordsFarsi()
        ];
    }

    private function getRelatedWords($word)
    {
        $network = $this->getNetworks();
        if (isset($network[$word])) {
            return $network[$word];
        } else {
            return [];
        }
    }

    private function addRelatedWordsToKeywords($keywords)
    {
        $allRelatedWords = [];

        foreach ($keywords as $keyword) {
            foreach ($this->getRelatedWords($keyword) as $word) {
                $allRelatedWords[] = $word;
            }
        }

        return $allRelatedWords;
    }

    private function staticResponses()
    {
        return [
            // Programming
            'php' => [
                'what?' => 'PHP is a server-side scripting language widely used for web development. It enables developers to create dynamic and interactive web pages by embedding code within HTML. PHP supports a variety of databases and powers popular platforms like WordPress, Laravel, and Drupal.',
                'why?' => 'PHP is popular due to its wide support for different databases and its use in widely adopted CMSs like WordPress and Laravel. It is highly versatile, open-source, and easy to integrate with various web technologies.',
                'how?' => 'PHP is used by embedding code inside HTML files. Developers can use PHP to interact with databases, validate forms, manage sessions, and generate dynamic content for websites.',
            ],
            'laravel' => [
                'what?' => 'Laravel is an open-source PHP framework designed to streamline web application development. It provides features like routing, templating, authentication, and database migrations. Laravel’s expressive syntax and rich ecosystem, including tools like Eloquent and Artisan, make it a favorite among developers.',
                'why?' => 'Laravel simplifies the development process by offering built-in solutions for common tasks, such as authentication and database management. Its elegant syntax and powerful ecosystem make it a popular choice for rapid development.',
                'how?' => 'Laravel uses a command-line tool called Artisan to create models, controllers, and migrations. Developers can use Eloquent ORM for database management and routing systems to handle HTTP requests and views.',
            ],
            'javascript' => [
                'what?' => 'JavaScript is a versatile, high-level programming language primarily used to make web pages interactive. It runs in web browsers as well as on servers through environments like Node.js. JavaScript supports a variety of frameworks like React, Vue.js, and Angular for building modern applications.',
                'why?' => 'JavaScript is essential for creating interactive, responsive websites. It allows for dynamic content updates, real-time data processing, and rich user interfaces, making it a cornerstone of modern web development.',
                'how?' => 'JavaScript is used in browsers to manipulate the DOM (Document Object Model), handle events, and update content dynamically. You can also use JavaScript on the server-side with Node.js for backend development.',
            ],
            'python' => [
                'what?' => 'Python is a powerful, easy-to-learn programming language with diverse applications. It is widely used in web development, data science, artificial intelligence, and machine learning. Python’s clean syntax and extensive libraries, such as NumPy and TensorFlow, make it highly popular among developers.',
                'why?' => 'Python’s simplicity, readability, and vast library support make it ideal for rapid development in various fields, including web development, AI, data analysis, and automation.',
                'how?' => 'Python is used with frameworks like Django or Flask for web development, and tools like TensorFlow or PyTorch for machine learning. Its clean syntax allows developers to write code faster and with fewer bugs.',
            ],
            'html' => [
                'what?' => 'HTML (HyperText Markup Language) is the foundation of web development, defining the structure of web pages. It is used to organize content with elements like headings, paragraphs, and multimedia. HTML is essential for building static websites and forms the backbone of all web applications.',
                'why?' => 'HTML is the fundamental building block for web development. Without HTML, the structure and content of web pages would not be defined, making it crucial for any web project.',
                'how?' => 'HTML uses tags to structure content. Tags like <div>, <p>, <img>, and <h1> define how content is displayed on a page, and how multimedia like images and videos are integrated.',
            ],
            'css' => [
                'what?' => 'CSS (Cascading Style Sheets) is a language for designing and styling web pages. It controls the layout, colors, fonts, and spacing to enhance user experience. CSS works with HTML to create visually appealing, responsive, and adaptive web designs.',
                'why?' => 'CSS is important for creating visually appealing websites and ensuring they are responsive across different screen sizes. Without CSS, web pages would look plain and unstyled.',
                'how?' => 'CSS is applied to HTML elements to style them. Developers use selectors to target specific elements and define their appearance, such as setting font sizes, colors, margins, and positions.',
            ],
            'nodejs' => [
                'what?' => 'Node.js is a runtime environment that allows JavaScript to be executed on the server-side. It uses an event-driven, non-blocking architecture to handle multiple requests efficiently. Node.js is widely used to build scalable web applications and APIs.',
                'why?' => 'Node.js is well-suited for applications that require high concurrency, such as real-time chat applications or APIs. Its non-blocking nature allows handling multiple requests simultaneously, making it fast and efficient.',
                'how?' => 'Node.js is used to build server-side applications with JavaScript. It uses the V8 JavaScript engine and enables asynchronous programming to handle many tasks at once without blocking the main thread.',
            ],
            'react' => [
                'what?' => 'React is a JavaScript library developed by Facebook for building user interfaces. It allows developers to create reusable UI components and manage the state of applications effectively. React is commonly used for creating fast, dynamic, and responsive web applications.',
                'why?' => 'React makes it easier to build complex UIs by breaking them into reusable components. Its virtual DOM ensures optimal performance by minimizing unnecessary rendering and improving user experience.',
                'how?' => 'React works by using components to render UI elements. Each component can have its own state and props, and when the state changes, React efficiently updates the UI using its virtual DOM.',
            ],
            'vuejs' => [
                'what?' => 'Vue.js is a progressive JavaScript framework used for building user interfaces and single-page applications. It is lightweight, easy to learn, and offers powerful features for state management and routing. Vue.js is popular for its simplicity and flexibility in modern web development.',
                'why?' => 'Vue.js is popular for its simplicity, flexibility, and ease of integration with other libraries. It allows developers to quickly build and maintain scalable web applications without much boilerplate code.',
                'how?' => 'Vue.js is used by creating Vue components, which can be used to define the structure, behavior, and styling of the UI. Vue’s two-way data binding makes it easy to keep the UI in sync with application data.',
            ],


            // Science
            'physics' => [
                'what?' => 'Physics is the study of matter, energy, and the interactions between them. It aims to understand how the universe behaves on both large and small scales, from the motion of planets to the behavior of subatomic particles.',
                'why?' => 'Physics is fundamental because it provides the framework for understanding the natural world and forms the foundation for many other scientific disciplines, including chemistry and engineering.',
                'how?' => 'Physics is explored through experiments, observations, and mathematical modeling. It uses the scientific method to test hypotheses and build theories that explain physical phenomena.',
            ],
            'chemistry' => [
                'what?' => 'Chemistry is the science that studies the properties, composition, and structure of substances. It also investigates how substances interact and transform during chemical reactions.',
                'why?' => 'Chemistry is crucial because it explains how matter interacts at the molecular and atomic levels, leading to innovations in medicine, materials science, and energy production.',
                'how?' => 'Chemistry is studied by conducting experiments that manipulate chemical reactions, analyze the products formed, and use instruments to observe molecular behavior.',
            ],
            'biology' => [
                'what?' => 'Biology is the study of life and living organisms, focusing on their structure, function, growth, evolution, and distribution.',
                'why?' => 'Biology is essential for understanding how living systems work and for addressing challenges like disease prevention, food production, and environmental conservation.',
                'how?' => 'Biology involves both experimental and observational methods, including dissection, microscopy, and genetic analysis to explore the complexities of life forms.',
            ],
            'astronomy' => [
                'what?' => 'Astronomy is the study of celestial bodies such as stars, planets, comets, and galaxies, as well as the phenomena that occur outside Earth\'s atmosphere.',
                'why?' => 'Astronomy helps us understand the origins, structure, and evolution of the universe, providing insights into our place in the cosmos.',
                'how?' => 'Astronomy is studied through observation using telescopes and space probes, as well as through theoretical models and simulations that explain cosmic phenomena.',
            ],
            'geology' => [
                'what?' => 'Geology is the science that studies the Earth, its materials, and the processes by which it evolves over time.',
                'why?' => 'Geology is vital for understanding natural disasters, resource management, and the planet\'s history, which can help with environmental conservation and risk mitigation.',
                'how?' => 'Geology is explored through field studies, lab analysis of rock and mineral samples, and geological mapping to understand Earth\'s structure and its dynamic processes.',
            ],
            'environmental_science' => [
                'what?' => 'Environmental science is the study of the environment and the ways in which human activity affects it. It integrates fields like biology, chemistry, and geology to address environmental issues.',
                'why?' => 'Environmental science is critical for understanding the impact of human activities on the planet and developing solutions to address problems like climate change, pollution, and resource depletion.',
                'how?' => 'Environmental science uses scientific research, data collection, and environmental monitoring techniques to assess the health of ecosystems and the impacts of human actions.',
            ],
            'mathematics' => [
                'what?' => 'Mathematics is the abstract science of numbers, quantity, shape, and arrangement. It is used to model real-world phenomena and solve practical problems in various fields.',
                'why?' => 'Mathematics is foundational to almost every field of science, technology, engineering, and economics, helping to quantify and solve complex problems.',
                'how?' => 'Mathematics is studied through theoretical exploration, proofs, and practical application to model real-world situations, such as in physics, engineering, and computer science.',
            ],
            'computer_science' => [
                'what?' => 'Computer Science is the study of algorithms, data structures, programming languages, and systems to solve problems through computational methods.',
                'why?' => 'Computer Science is at the heart of modern innovation, driving advances in technology, artificial intelligence, data analysis, and automation.',
                'how?' => 'Computer Science is explored through programming, algorithm design, and developing systems that solve real-world problems, using both theory and practical coding skills.',
            ],

            // Cryptocurrency
            'blockchain' => [
                'what?' => 'A decentralized, immutable ledger for recording and verifying transactions.',
                'why?' => 'Secure due to cryptography and consensus mechanisms. Transparent due to immutability and public access.',
                'how?' => 'Key components include blocks, hashes, and miners.',
            ],
            'cryptocurrency' => [
                'what?' => 'Digital or virtual currencies that use cryptography for security.',
                'how?' => 'Decentralized, offering benefits like censorship resistance.',
                'why?' => 'Transactions are verified and secured using cryptography (e.g., digital signatures).',
            ],
            'smart_contract' => [
                'what?' => 'Self-executing contracts with terms written directly into code.',
                'how?' => 'Important for enabling automation and trust in decentralized applications.',
                'why?' => 'Execute transactions based on predefined conditions.',
            ],
            'decentralized_finance' => [
                'what?' => 'Financial services built on blockchain technology.',
                'how?' => 'Aims to disrupt traditional finance by offering greater accessibility and transparency.',
                'why?' => 'Eliminates intermediaries like banks and brokers.',
            ],
            'nft' => [
                'what?' => 'Unique digital assets representing ownership of real-world or digital items.',
                'how?' => 'Value determined by factors like scarcity, uniqueness, and community.',
                'why?' => 'Created, bought, and sold on blockchain networks.',
            ],
            'mining' => [
                'what?' => 'The process of verifying and adding new transactions to the blockchain.',
                'why?' => 'Essential for maintaining the security and integrity of the blockchain.',
                'how?' => 'Works by solving complex computational puzzles in proof-of-work systems.',
            ],
            'proof_of_work' => [
                'what?' => 'A consensus mechanism requiring miners to solve complex puzzles.',
                'why?' => 'Energy-intensive due to the computational power required.',
                'how?' => 'Validates transactions and creates new blocks on the blockchain.',
            ],
        ];
    }

    private function getNetworks()
    {
        return [
            'laravel' => ['vuejs', 'artisan', 'eloquent', 'blade', 'livewire'],
            'nodejs' => ['javascript', 'express', 'nestjs', 'koa', 'socket.io'],
            'react' => ['javascript', 'redux', 'typescript', 'next.js'],
            'vuejs' => ['javascript', 'vuex', 'nuxt.js', 'laravel'],
            'python' => ['django', 'flask', 'pandas', 'numpy', 'scikit-learn', 'tensorflow'],
            'django' => ['python', 'rest-framework', 'jinja', 'postgresql'],
            'flask' => ['python', 'jinja', 'sqlalchemy'],
            'html' => ['css', 'javascript', 'bootstrap', 'tailwindcss'],
            'css' => ['html', 'javascript', 'sass', 'less', 'tailwindcss'],
            'nodejs' => ['javascript', 'express', 'mongodb', 'sequelize'],
            'mysql' => ['sql', 'php', 'laravel'],
            'typescript' => ['javascript', 'angular', 'react'],
            'sass' => ['css', 'html', 'javascript'],
            'bootstrap' => ['html', 'css', 'javascript'],
            'tailwindcss' => ['html', 'css', 'javascript', 'react'],

            // Science
            'physics' => ['chemistry', 'biology', 'astronomy', 'mathematics', 'engineering', 'quantum mechanics', 'thermodynamics'],
            'chemistry' => ['physics', 'biology', 'biochemistry', 'organic chemistry', 'inorganic chemistry', 'materials science'],
            'biology' => ['chemistry', 'physics', 'medicine', 'genetics', 'ecology', 'neuroscience'],
            'astronomy' => ['physics', 'mathematics', 'astrophysics', 'space science'],
            'mathematics' => ['physics', 'engineering', 'statistics', 'computer science', 'algebra', 'calculus', 'geometry'],
            'engineering' => ['physics', 'mathematics', 'mechanical engineering', 'civil engineering', 'electrical engineering', 'software engineering'],
            'medicine' => ['biology', 'chemistry', 'pharmacology', 'genetics', 'neuroscience'],
            'genetics' => ['biology', 'medicine', 'biotechnology', 'bioinformatics'],
            'neuroscience' => ['biology', 'medicine', 'psychology', 'cognitive science'],
            'computer science' => ['mathematics', 'engineering', 'artificial intelligence', 'machine learning', 'data science'],
            'statistics' => ['mathematics', 'data science', 'probability', 'machine learning'],
            'data science' => ['statistics', 'computer science', 'machine learning', 'big data'],

            // Cryptocurrency
            'cryptocurrency' => ['bitcoin', 'ethereum', 'blockchain', 'altcoin', 'stablecoin'],
            'bitcoin' => ['cryptocurrency', 'blockchain', 'mining', 'wallet', 'lightning network'],
            'ethereum' => ['cryptocurrency', 'blockchain', 'smart contract', 'dapps', 'nft'],
            'blockchain' => ['cryptocurrency', 'bitcoin', 'ethereum', 'decentralization', 'ledger', 'consensus algorithm'],
            'smart contract' => ['ethereum', 'blockchain', 'dapps', 'solidity', 'defi'],
            'altcoin' => ['cryptocurrency', 'litecoin', 'ripple', 'cardano', 'polkadot'],
            'stablecoin' => ['cryptocurrency', 'tether', 'usdc', 'decentralized finance', 'fiat-backed'],
            'mining' => ['bitcoin', 'blockchain', 'proof of work', 'hashrate', 'energy consumption'],
            'wallet' => ['cryptocurrency', 'bitcoin', 'ethereum', 'hardware wallet', 'software wallet'],
            'decentralized finance' => ['blockchain', 'smart contract', 'ethereum', 'liquidity pool', 'yield farming'],
            'nft' => ['ethereum', 'blockchain', 'art', 'digital assets', 'marketplace'],
            'consensus algorithm' => ['blockchain', 'proof of work', 'proof of stake', 'delegated proof of stake'],
            'proof of work' => ['bitcoin', 'blockchain', 'mining', 'hashrate'],
            'proof of stake' => ['blockchain', 'ethereum', 'consensus algorithm', 'staking', 'energy efficiency'],
            'staking' => ['proof of stake', 'cryptocurrency', 'reward', 'validator'],
            'defi' => ['decentralized finance', 'ethereum', 'liquidity', 'yield farming', 'lending'],
            'liquidity pool' => ['defi', 'decentralized exchange', 'blockchain', 'yield farming'],
            'decentralized exchange' => ['defi', 'blockchain', 'trading', 'liquidity pool'],
            'trading' => ['cryptocurrency', 'bitcoin', 'market analysis', 'technical indicators'],
        ];
    }

    private function stopWordsFarsi()
    {
        return [
            "از",
            "در",
            "و",
            "با",
            "برای",
            "به",
            "که",
            "این",
            "آن",
            "تا",
            "شود",
            "هم",
            "همچنین",
            "اینکه",
            "چرا",
            "چه",
            "اگر",
            "یا",
            "نیز",
            "او",
            "همه",
            "اگرچه",
            "اما",
            "بیشتر",
            "کمتر",
            "حالا",
            "باشه",
            "کنید",
            "کردن",
            "خواهد",
            "میکند",
            "آیا",
            "هست",
            "هستند",
            "داشت",
            "داشتن",
            "چند",
            "چرا",
            "چگونه",
            "هنوز",
            "اینکه",
            "پس",
            "گفت",
            "گفته",
            "بود",
            "بوده",
            "برای",
            "مگر",
            "میدانید",
            "نبود",
            "نمی",
            "کرد",
            "کنیم",
            "نکنید",
            "مانند",
            "فقط",
            "دنبال",
            "چطور",
            "چیزی",
            "اینطور",
            "چیزی",
            "بین",
            "سایر",
            "چندین",
            "کنیم",
            "درحالی‌که",
            "قبل",
            "بعد",
            "درست",
            "نمی‌تواند",
            "چنانچه",
            "بدون",
            "طی",
            "برخی",
            "دراین",
            "یا",
            "تحت",
            "حال",
            "کلی",
            "دیگر",
            "مانند",
            "علت",
            "اینجا",
            "چطور",
            "کدام",
            "چه چیزی",
            "چگونه",
            "هریک",
            "کل",
            "یعنی",
            "زیرا",
            "درباره",
            "چطور"
        ];
    }

    private function stopWordsEnglish()
    {
        return  [
            "a",
            "about",
            "above",
            "after",
            "again",
            "against",
            "all",
            "am",
            "an",
            "and",
            "any",
            "are",
            "aren't",
            "as",
            "at",
            "be",
            "because",
            "been",
            "before",
            "being",
            "below",
            "between",
            "both",
            "but",
            "by",
            "can't",
            "cannot",
            "could",
            "couldn't",
            "did",
            "didn't",
            "do",
            "does",
            "doesn't",
            "doing",
            "don't",
            "down",
            "during",
            "each",
            "few",
            "for",
            "from",
            "further",
            "had",
            "hadn't",
            "has",
            "hasn't",
            "have",
            "haven't",
            "having",
            "he",
            "he'd",
            "he'll",
            "he's",
            "her",
            "here",
            "here's",
            "hers",
            "herself",
            "him",
            "himself",
            "his",
            "how",
            "how's",
            "i",
            "i'd",
            "i'll",
            "i'm",
            "i've",
            "if",
            "in",
            "into",
            "is",
            "isn't",
            "it",
            "it's",
            "its",
            "itself",
            "let's",
            "me",
            "more",
            "most",
            "mustn't",
            "my",
            "myself",
            "no",
            "nor",
            "not",
            "of",
            "off",
            "on",
            "once",
            "only",
            "or",
            "other",
            "ought",
            "our",
            "ours",
            "ourselves",
            "out",
            "over",
            "own",
            "same",
            "shan't",
            "she",
            "she'd",
            "she'll",
            "she's",
            "should",
            "shouldn't",
            "so",
            "some",
            "such",
            "than",
            "that",
            "that's",
            "the",
            "their",
            "theirs",
            "them",
            "themselves",
            "then",
            "there",
            "there's",
            "these",
            "they",
            "they'd",
            "they'll",
            "they're",
            "they've",
            "this",
            "those",
            "through",
            "to",
            "too",
            "under",
            "until",
            "up",
            "very",
            "was",
            "wasn't",
            "we",
            "we'd",
            "we'll",
            "we're",
            "we've",
            "were",
            "weren't",
            "what",
            "what's",
            "when",
            "when's",
            "where",
            "where's",
            "which",
            "while",
            "who",
            "who's",
            "whom",
            "why",
            "why's",
            "with",
            "won't",
            "would",
            "wouldn't",
            "you",
            "you'd",
            "you'll",
            "you're",
            "you've",
            "your",
            "yours",
            "yourself",
            "yourselves"
        ];
    }
}
