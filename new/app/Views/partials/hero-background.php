<?php /** Decorative hero layer — logistics geometry, no impact on content readability */ ?>
<div class="hero-bg" aria-hidden="true">
  <div class="hero-bg__gradient"></div>
  <div class="hero-bg__grid"></div>

  <!-- Supply chain network lines -->
  <svg class="hero-bg__network" viewBox="0 0 1200 600" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="heroLine" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="0%" stop-color="#C59E5F" stop-opacity="0"/>
        <stop offset="50%" stop-color="#C59E5F" stop-opacity="0.35"/>
        <stop offset="100%" stop-color="#C59E5F" stop-opacity="0"/>
      </linearGradient>
    </defs>
    <g fill="none" stroke="url(#heroLine)" stroke-width="1" class="hero-bg__routes">
      <path d="M80 420 L280 320 L520 380 L720 240 L980 300 L1120 180"/>
      <path d="M40 280 L220 200 L400 260 L640 140 L900 200"/>
      <path d="M200 520 L420 440 L600 480 L840 360"/>
    </g>
    <g fill="#C59E5F" class="hero-bg__nodes">
      <circle cx="280" cy="320" r="4" opacity="0.5"/>
      <circle cx="520" cy="380" r="4" opacity="0.4"/>
      <circle cx="720" cy="240" r="5" opacity="0.55"/>
      <circle cx="400" cy="260" r="3" opacity="0.35"/>
      <circle cx="840" cy="360" r="4" opacity="0.45"/>
    </g>
  </svg>

  <!-- Soft geometric accents -->
  <div class="hero-bg__shapes">
    <span class="hero-bg__shape hero-bg__shape--1"></span>
    <span class="hero-bg__shape hero-bg__shape--2"></span>
    <span class="hero-bg__shape hero-bg__shape--3"></span>
  </div>

  <!-- Logistics iconography (desktop / tablet) -->
  <div class="hero-bg__icons">
    <div class="hero-bg__icon hero-bg__icon--globe">
      <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.25">
        <circle cx="32" cy="32" r="22"/>
        <path d="M10 32h44M32 10c6 8 6 36 0 44s-6-36 0-44M32 10c-6 8-6 36 0 44"/>
      </svg>
    </div>
    <div class="hero-bg__icon hero-bg__icon--warehouse">
      <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.25">
        <path d="M8 28 L32 14 L56 28 V54 H8 Z"/>
        <path d="M22 54 V38 H42 V54"/>
        <path d="M16 32h8M40 32h8"/>
      </svg>
    </div>
    <div class="hero-bg__icon hero-bg__icon--truck">
      <svg viewBox="0 0 80 48" fill="none" stroke="currentColor" stroke-width="1.25">
        <path d="M4 32h42l10-14h16v14"/>
        <rect x="4" y="18" width="44" height="14" rx="1"/>
        <circle cx="18" cy="36" r="5"/><circle cx="58" cy="36" r="5"/>
      </svg>
    </div>
    <div class="hero-bg__icon hero-bg__icon--boxes">
      <svg viewBox="0 0 56 56" fill="none" stroke="currentColor" stroke-width="1.25">
        <path d="M6 18 L28 8 L50 18 V40 L28 50 L6 40 Z"/>
        <path d="M28 8v22M6 18l22 10 22-10"/>
      </svg>
    </div>
  </div>

  <div class="hero-bg__glow"></div>
</div>
