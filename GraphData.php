<?php

namespace App\Services;

use Carbon\Carbon;

class GraphData
{
    private $dateFormat;
    private $dateColumn;
    private $resultColumn;
    private $timeData = [];

    public function set($for, $using, $format = 'd M')
    {
        $this->dateFormat = $format;
        $this->dateColumn = $using;
        $this->resultColumn = $for;
    }

    private function generate($start, $end)
    {
        while (!$start->isSameDay($end)) {
            $this->timeData[$start->format($this->dateFormat)] = 0;
            $start->addDay();
        }
        return $this->timeData;
    }

    private function create($data)
    {
        foreach ($data as $item) {
            $current = Carbon::parse($item[$this->dateColumn])->format($this->dateFormat);
            $this->timeData[$current] = $item[$this->resultColumn];
        }
        return $this->timeData;
    }

    /**
     * Get a month of time data
     *
     * @param Array $data
     * @return array
     */
    public function getMonthly($data)
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $this->generate($start, $end);

        return $this->create($data);
    }

    /**
     * Get a week of time data
     *
     * @param Array $data
     * @return array
     */

    public function getWeekly($data)
    {
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        $this->generate($start, $end);

        return $this->create($data);
    }

    /**
     * Get a custom days of time data
     *
     * @param Array $data
     * @return array
     */
    public function getDays($data, $day = 15)
    {
        $start = Carbon::now()->subDays($day);
        $end = Carbon::now();

        $this->generate($start, $end);

        return $this->create($data);
    }
}
