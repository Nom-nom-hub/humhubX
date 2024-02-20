<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\components;

use humhub\interfaces\SearchRecordInterface;
use Yii;
use yii\helpers\Url;

/**
 * SearchProvider
 *
 * @author luke
 * @since 1.16
 */
abstract class SearchProvider
{
    public ?string $keyword = null;
    public int $pageSize = 4;

    protected ?int $totalCount = null;

    /**
     * @var SearchRecordInterface[]|null $results
     */
    protected ?array $results = null;

    /**
     * @var string|null $route Route to the searching page with all filters
     */
    protected ?string $route = null;

    /**
     * Get name of the Search Provider
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Search results
     *
     * @return void
     */
    abstract public function search(): void;

    /**
     * Get URL to all results
     *
     * @return string
     */
    public function getAllResultsUrl(): string
    {
        $params = [$this->route];
        if ($this->keyword !== null && $this->hasRecords()) {
            $params['keyword'] = $this->keyword;
        }

        return Url::to($params);
    }

    /**
     * Get text of link to go to all results
     *
     * @return string
     */
    public function getAllResultsText(): string
    {
        return $this->hasRecords()
            ? Yii::t('base', 'Show all results')
            : Yii::t('base', 'Advanced search');
    }

    /**
     * Check if a searching has been done
     *
     * @return bool
     */
    public function isSearched(): bool
    {
        return $this->results !== null;
    }

    /**
     * Get number of results
     *
     * @return int
     */
    public function getTotal(): int
    {
        return isset($this->totalCount) ? (int) $this->totalCount : 0;
    }

    /**
     * Has at least one searched record
     *
     * @return bool
     */
    public function hasRecords(): bool
    {
        return !empty($this->results);
    }

    /**
     * Get searched records
     *
     * @return SearchRecordInterface[]
     */
    public function getRecords(): array
    {
        return $this->results ?? [];
    }
}
