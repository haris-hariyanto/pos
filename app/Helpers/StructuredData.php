<?php

namespace App\Helpers;

class StructuredData
{
    public $structuredData = [];

    /**
     * $data = [
     *  'name' => 'url',
     *  'name' => 'url',
     * ]
     */
    public function breadcrumb($data = [], $removeHome = false)
    {
        $result = [];
        $result['@context'] = 'https://schema.org';
        $result['@type'] = 'BreadcrumbList';
        $result['itemListElement'] = [];

        $counter = 1;
        foreach ($data as $name => $item) {
            if ($removeHome && $name == __('Home')) {
                continue;
            }

            $itemListElement = [];
            $itemListElement['@type'] = 'ListItem';
            $itemListElement['position'] = $counter;
            $itemListElement['name'] = htmlspecialchars($name);

            if (!empty(trim($item))) {
                $itemListElement['item'] = $item;
            }

            $result['itemListElement'][] = $itemListElement;

            $counter++;
        }

        $this->structuredData[] = $result;
    }

    /**
     * $data = [
     *  'name' => '', // Required
     *  'description' => '', // Optional
     *  'photo' => '', // Optional
     *  'price' => '', // Optional
     *  'star' => '', // Optional
     *  'country' => '', // Optional
     *  'city' => '', // Optional
     *  'state' => '', // Optional
     *  'postalCode' => '', // Optional
     *  'address' => '', // Optional
     *  'numberReviews' => '', // Optional
     *  'ratingAverage' => '', // Optional
     * ]
     */
    public function hotel($data = [])
    {
        $result = [];
        $result['@context'] = 'https://schema.org';
        $result['@type'] = 'Hotel';
        $result['name'] = $data['name'];

        if (!empty($data['numberReviews']) && !empty($data['ratingAverage'])) {
            $result['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $data['ratingAverage'],
                'ratingCount' => $data['numberReviews'],
                'bestRating' => 10,
            ];
        }
        
        if (!empty($data['description'])) {
            $result['description'] = $data['description'];
        }

        if (!empty($data['photo'])) {
            $result['photo'] = $data['photo'];
            $result['image'] = $data['photo'];
        }

        if (!empty($data['price'])) {
            $result['priceRange'] = $data['price'];
        }

        if (!empty($data['star'])) {
            $result['starRating'] = [
                '@type' => 'Rating',
                'ratingValue' => $data['star'],
            ];
        }

        if (!empty($data['country']) || !empty($data['city']) || !empty($data['state']) || !empty($data['postalCode']) || !empty($data['address'])) {
            $result['address'] = [];
            $result['address']['@type'] = 'PostalAddress';
            
            if (!empty($data['country'])) {
                $result['address']['addressCountry'] = $data['country'];
            }

            if (!empty($data['city'])) {
                $result['address']['addressLocality'] = $data['city'];
            }

            if (!empty($data['state'])) {
                $result['address']['addressRegion'] = $data['state'];
            }

            if (!empty($data['postalCode'])) {
                $result['address']['postalCode'] = $data['postalCode'];
            }

            if (!empty($data['address'])) {
                $result['address']['streetAddress'] = $data['address'];
            }
        }

        $this->structuredData[] = $result;
    }

    /**
     * $data = [
     *  'questions' => [
     *      ['question' => '', 'answer' => ''],
     *      ['question' => '', 'answer' => ''],
     *  ], // Required
     *  'title' => '', // Optional
     *  'subject' => '', // Optional
     *  'level' => '', // Optional
     * ]
     */
    public function educationQA($data = [])
    {
        $result = [];
        $result['@context'] = 'https://schema.org';
        $result['@type'] = 'Quiz';

        if (!empty($data['title'])) {
            $result['about'] = [
                '@type' => 'Thing',
                'name' => $data['title'],
            ];
        }

        if (!empty($data['subject']) || !empty($data['level'])) {
            $result['educationalAlignment'] = [];

            if (!empty($data['subject'])) {
                $result['educationalAlignment'][] = [
                    '@type' => 'AlignmentObject',
                    'alignmentType' => 'educationalSubject',
                    'targetName' => $data['subject'],
                ];
            }

            if (!empty($data['level'])) {
                $result['educationalAlignment'][] = [
                    '@type' => 'AlignmentObject',
                    'alignmentType' => 'educationalLevel',
                    'targetName' => $data['level'],
                ];
            }
        }

        $result['hasPart'] = [];

        foreach ($data['questions'] as $question) {
            $result['hasPart'][] = [
                '@context' => 'https://schema.org',
                '@type' => 'Question',
                'eduQuestionType' => 'Flashcard',
                'text' => $question['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $question['answer'],
                ],
            ];
        }

        $this->structuredData[] = $result;
    }

    /**
     * $data = [
     *  'questionShort' => '', // Required
     *  'questionFull' => '', // Optional
     *  'answerCount' => 0, // Required
     *  'upvoteCount' => 0, // Optional
     *  'answers' => [
     *      [
     *          'text' => '', // Required
     *          'upvoteCount' => 0, // Optional
     *          'url' => '', // Optional
     *          'isTop' => false, // Optional
     *      ],
     *  ], // Required
     * ]
     */
    public function QA($data = [])
    {
        $result = [];
        $result['@context'] = 'https://schema.org';
        $result['@type'] = 'QAPage';

        $mainEntity = [];
        $mainEntity['@type'] = 'Question';
        $mainEntity['name'] = htmlspecialchars($data['questionShort']);

        if (!empty($data['questionFull'])) {
            $mainEntity['text'] = htmlspecialchars($data['questionFull']);
        }

        $mainEntity['answerCount'] = $data['answerCount'];

        if (isset($data['upvoteCount'])) {
            $mainEntity['upvoteCount'] = $data['upvoteCount'];
        }

        $acceptedAnswer = false;
        $suggestedAnswers = [];

        foreach ($data['answers'] as $answer) {
            if ($acceptedAnswer == false && isset($answer['isTop']) && $answer['isTop']) {
                $acceptedAnswer['@type'] = 'Answer';
                $acceptedAnswer['text'] = htmlspecialchars($answer['text']);

                if (isset($answer['upvoteCount'])) {
                    $acceptedAnswer['upvoteCount'] = $answer['upvoteCount'];
                }

                if (!empty($answer['url'])) {
                    $acceptedAnswer['url'] = $answer['url'];
                }

                $mainEntity['acceptedAnswer'] = $acceptedAnswer;
            }
            else {
                $answerData = [];
                $answerData['@type'] = 'Answer';
                $answerData['text'] = htmlspecialchars($answer['text']);

                if (isset($answer['upvoteCount'])) {
                    $answerData['upvoteCount'] = $answer['upvoteCount'];
                }

                if (!empty($answer['url'])) {
                    $answerData['url'] = $answer['url'];
                }

                $suggestedAnswers[] = $answerData;
            }
        }

        $mainEntity['suggestedAnswer'] = $suggestedAnswers;
        
        $result['mainEntity'] = $mainEntity;

        $this->structuredData[] = $result;
    }

    public function render()
    {
        if (count($this->structuredData) > 0) {
            return '<script type="application/ld+json">' . json_encode($this->structuredData, JSON_UNESCAPED_SLASHES) . '</script>';
        }
    }
}