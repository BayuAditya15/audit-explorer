---
name: Governance Precision
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#434655'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#565e74'
  on-secondary: '#ffffff'
  secondary-container: '#dae2fd'
  on-secondary-container: '#5c647a'
  tertiary: '#46566c'
  on-tertiary: '#ffffff'
  tertiary-container: '#5e6e85'
  on-tertiary-container: '#e9f0ff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#dae2fd'
  secondary-fixed-dim: '#bec6e0'
  on-secondary-fixed: '#131b2e'
  on-secondary-fixed-variant: '#3f465c'
  tertiary-fixed: '#d3e4fe'
  tertiary-fixed-dim: '#b7c8e1'
  on-tertiary-fixed: '#0b1c30'
  on-tertiary-fixed-variant: '#38485d'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 2rem
    fontWeight: '700'
    lineHeight: 2.5rem
    letterSpacing: -0.025em
  headline-lg:
    fontFamily: Inter
    fontSize: 1.5rem
    fontWeight: '600'
    lineHeight: 2rem
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Inter
    fontSize: 1.25rem
    fontWeight: '600'
    lineHeight: 1.75rem
    letterSpacing: -0.015em
  headline-sm:
    fontFamily: Inter
    fontSize: 1.125rem
    fontWeight: '600'
    lineHeight: 1.5rem
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Inter
    fontSize: 1rem
    fontWeight: '400'
    lineHeight: 1.5rem
  body-md:
    fontFamily: Inter
    fontSize: 0.875rem
    fontWeight: '400'
    lineHeight: 1.25rem
  body-sm:
    fontFamily: Inter
    fontSize: 0.75rem
    fontWeight: '400'
    lineHeight: 1rem
  label-lg:
    fontFamily: Inter
    fontSize: 0.875rem
    fontWeight: '600'
    lineHeight: 1.25rem
    letterSpacing: 0.01em
  label-md:
    fontFamily: Inter
    fontSize: 0.75rem
    fontWeight: '600'
    lineHeight: 1rem
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Inter
    fontSize: 0.6875rem
    fontWeight: '600'
    lineHeight: 0.875rem
    letterSpacing: 0.04em
  code-sm:
    fontFamily: JetBrains Mono
    fontSize: 0.75rem
    fontWeight: '500'
    lineHeight: 1rem
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  gutter: 1.25rem
  gutter-desktop: 1.5rem
  margin: 1rem
  margin-desktop: 2rem
  space-2xs: 0.125rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 0.75rem
  space-lg: 1rem
  space-xl: 1.5rem
  space-2xl: 2rem
  space-3xl: 3rem
---

## Brand & Style

This design system establishes an authoritative, institutional, and meticulously structured visual foundation for IT governance, risk management, and compliance auditing. Built to manage complex hierarchical frameworks like COBIT 2019, the visual identity embodies rigorous precision, objective clarity, and trust.

### Visual Aesthetic & Philosophy
The aesthetic combines **Corporate Modern** with **Technical Minimalism**. It rejects decorative flourishes in favor of structural clarity, dense tabular organization, and high information throughput. The interface operates as an audit-grade instrument: balanced, utilitarian, and distraction-free.

### Brand Tenets
- **Institutional Authority:** Visual solidity through deep structural slate tones, grounding enterprise data in unshakeable dependability.
- **Architectural Hierarchy:** Strict containment models that reflect cascading governance levels—from executive principles down to individual control objectives and evidence artifacts.
- **Cognitive Economy:** High density balanced with intentional micro-spacing to ensure audit professionals navigate vast compliance matrices without fatigue.

## Colors

The palette establishes clear separation between global control boundaries and analytical surfaces.

### Core Canvas & Structure
- **Sidebar & Command Shell (`#0F172A` / `slate-900`):** Acts as the foundational anchor for root navigation, global context switchers, and platform metadata.
- **Application Canvas (`#F8FAFC` / `slate-50`):** Neutral, soft background that provides contrast for white analytical cards without harsh glare.
- **Surfaces & Cards (`#FFFFFF`):** High-clarity elevated planes designed for data evaluation and interactive tables.
- **Structural Borders (`#E2E8F0` / `slate-200`):** Defines clean containment boundaries across panels, tables, and nested sub-matrices.

### Typography & Content Color
- **Primary Text (`#0F172A` to `#1E293B`):** High legibility text for controls, framework codes, and table headers.
- **Secondary & Muted Text (`#64748B` / `slate-500`):** Secondary metadata, inactive states, table column footers, and timestamp tracking.

### Primary Action & Interaction
- **Primary Accent (`#2563EB` / `blue-600`):** Used strictly for high-priority interactive calls-to-action, active breadcrumbs, and selected matrix states. Hover states shift to `#1D4ED8` (`blue-700`).

### COBIT Domain Semantic Palette
Distinct, subdued pastel-backed indicators for the five COBIT core governance and management domains:
- **Evaluate, Direct and Monitor (EDM):** Deep Violet (`#7C3AED` text on `#F5F3FF` background, border `#DDD6FE`).
- **Align, Plan and Organize (APO):** Crisp Indigo (`#4F46E5` text on `#EEF2FF` background, border `#C7D2FE`).
- **Build, Acquire and Implement (BAI):** Cobalt Blue (`#2563EB` text on `#EFF6FF` background, border `#BFDBFE`).
- **Deliver, Service and Support (DSS):** Emerald Green (`#059669` text on `#ECFDF5` background, border `#A7F3D0`).
- **Monitor, Evaluate and Assess (MEA):** Warm Amber (`#D97706` text on `#FFFBEB` background, border `#FDE68A`).

## Typography

The type system is engineered around tabular alignment, dense reporting hierarchies, and uninterrupted scanning. `Inter` serves as the primary system typeface due to its optimized x-height, neutral letterforms, and comprehensive OpenType tabular figures.

### Typographic Hierarchy & Usage Rules
- **Numerical Alignment:** All numerical outputs, scores, maturity levels (0–5), and control IDs (e.g., `EDM01.01`, `DSS05.03`) must employ `tnum` (tabular figures) or the dedicated `code-sm` token.
- **Section & Header Constraints:** Header sizes remain compact to conserve vertical viewport height for data tables and alignment matrices.
- **Uppercase Metadata:** Use `label-sm` with explicit uppercase styling for framework labels, status indicators, and audit table column headers.

## Layout & Spacing

This design system uses a structured shell layout with a permanent dark sidebar and a fluid, data-dense work area.

### Grid & Layout Structure
- **Global Shell:** Permanent 260px wide dark navigation sidebar docked to the left, collapsible to a 64px icon-rail for data-intensive views.
- **Content Area:** Fluid multi-column layout structured using 12-column responsive grids with `1.5rem` gutters on viewports `>= 1280px`.
- **Top Utility Header:** Fixed 56px height bar housing breadcrumbs, tenant switcher, audit cycle status, and global search.

### Responsive Breakpoints & Adaptations
- **Desktop (>= 1280px):** Full multi-pane workflow (sidebar + main matrix view + contextual side-drawer for control evaluations).
- **Laptop (1024px - 1279px):** Contextual inspection drawers collapse into overlay slide-overs. Margins compress to `1.5rem`.
- **Tablet & Compact (<= 1023px):** Global sidebar collapses into an overlay menu. Tables gain horizontal panning with sticky control-code columns.

## Elevation & Depth

Visual separation relies on structural boundaries and minimal elevation rather than heavy drop-shadows, ensuring a sharp, professional finish.

### Boundary Model
- **Structural Outlines:** Use `#E2E8F0` (`slate-200`) borders on cards, data cells, toolbars, and dividers.
- **Interactive Planes (Elevated):** Hoverable rows and actionable items utilize subtle surface transitions (`#F8FAFC` to `#FFFFFF`) accompanied by `shadow-sm` (`0 1px 2px 0 rgb(0 0 0 / 0.05)`).
- **Overlays & Slide-Overs:** Drawers for control objective details and modal dialogues use a sharp, controlled shadow (`0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)`) with a high-contrast `#0F172A` backdrop at 40% opacity.

## Shapes

The design system maintains a refined, low-curvature geometric profile (`roundedness: 1` — Soft / 4px base).

- **Controls & Buttons:** 4px (`0.25rem`) radius provides subtle softening without sacrificing an enterprise feel.
- **Cards & Data Tables:** 6px to 8px (`rounded-md` to `rounded-lg`) outer perimeter corners, while internal tabular cells remain squared for precise grid alignment.
- **Domain Badges & Status Chips:** 4px radius with strict border containment (avoid full-pill radii to maintain an institutional aesthetic).

## Components

### Buttons
- **Primary:** Background `#2563EB`, text `#FFFFFF`, radius 4px, height 36px, horizontal padding `1rem`. Active state `#1D4ED8`. Focus ring: 2px offset with `#2563EB`.
- **Secondary / Outline:** Background `#FFFFFF`, border 1px solid `#E2E8F0`, text `#1E293B`. Hover: `#F8FAFC`, border `#CBD5E1`.
- **Danger (Audit Exceptions):** Background `#FEF2F2`, text `#DC2626`, border 1px solid `#FCA5A5`.

### Chips & Badges
- **COBIT Domain Tags:** Monospace category key (e.g., `APO01`) + short title, 4px border-radius, height 22px, padding `0.25rem 0.5rem`.
  - *EDM:* `#F5F3FF` bg, `#7C3AED` text, `#DDD6FE` border.
  - *APO:* `#EEF2FF` bg, `#4F46E5` text, `#C7D2FE` border.
  - *BAI:* `#EFF6FF` bg, `#2563EB` text, `#BFDBFE` border.
  - *DSS:* `#ECFDF5` bg, `#059669` text, `#A7F3D0` border.
  - *MEA:* `#FFFBEB` bg, `#D97706` text, `#FDE68A` border.
- **Maturity Level Indicators:** Compact squared badges displaying levels `0` through `5` with progressive fill intensity from `#94A3B8` (Incomplete) to `#2563EB` (Optimizing).

### Form Controls & Inputs
- **Text Inputs:** Height 36px, background `#FFFFFF`, border 1px solid `#CBD5E1`, border radius 4px, text `#0F172A`, placeholder `#94A3B8`. Active focus: border `#2563EB`, ring 1px `#2563EB`.
- **Checkboxes & Radios:** 16px square/circle, border 1.5px solid `#CBD5E1`, filled with `#2563EB` when checked with a crisp white tick mark.

### Data Tables & Control Matrices
- **Table Headers:** Background `#F8FAFC`, 1px solid bottom border `#E2E8F0`, uppercase text `#64748B`, font-size `0.75rem`, tracking `0.05em`.
- **Table Rows:** Base background `#FFFFFF`, alternating hover highlight `#F8FAFC`, border bottom 1px solid `#F1F5F9`. Compact row height: 44px; standard row height: 56px.
- **Cell Content:** Left-aligned text, right-aligned monetary and percentage values, centered domain indicators.

### Cards & Panels
- Background `#FFFFFF`, border 1px solid `#E2E8F0`, border-radius 6px, padding `1.25rem`. Headers inside cards feature an integrated bottom separator line and an action slot on the right.