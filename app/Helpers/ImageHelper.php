<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Generate full image URL from relative path
     * 
     * @param string|null $relativePath - e.g., 'uploads/avatars/image.jpg'
     * @return string|null - Full URL or null if path is empty
     */
    public static function getFullUrl($relativePath)
    {
        if (empty($relativePath)) {
            return null;
        }

        // If already a full URL (for backward compatibility)
        if (str_starts_with($relativePath, 'http://') || str_starts_with($relativePath, 'https://')) {
            return $relativePath;
        }

        // Remove leading slash if present
        $relativePath = ltrim($relativePath, '/');

        return asset($relativePath);
    }

    /**
     * Extract relative path from full URL or return as-is if already relative
     * Useful for migration from old absolute URLs to relative paths
     * 
     * @param string|null $urlOrPath
     * @return string|null
     */
    public static function getRelativePath($urlOrPath)
    {
        if (empty($urlOrPath)) {
            return null;
        }

        // Already a relative path
        if (!str_starts_with($urlOrPath, 'http://') && !str_starts_with($urlOrPath, 'https://')) {
            return $urlOrPath;
        }

        // Extract path from URL
        $parsed = parse_url($urlOrPath);
        if (isset($parsed['path'])) {
            // Remove leading slash and 'public/' if present
            $path = ltrim($parsed['path'], '/');
            $path = preg_replace('#^public/#', '', $path);
            return $path;
        }

        return $urlOrPath;
    }
}
