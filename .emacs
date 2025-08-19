(custom-set-variables
 ;; custom-set-variables was added by Custom.
 ;; If you edit it by hand, you could mess it up, so be careful.
 ;; Your init file should contain only one such instance.
 ;; If there is more than one, they won't work right.
 '(package-selected-packages '(rust-mode projectile web-mode counsel ivy cmake-mode)))
(custom-set-faces
 ;; custom-set-faces was added by Custom.
 ;; If you edit it by hand, you could mess it up, so be careful.
 ;; Your init file should contain only one such instance.
 ;; If there is more than one, they won't work right.
 )

;; Enable line numbers
(global-display-line-numbers-mode t)

(setq package-check-signature nil)

(require 'package)

(setq package-archives
      '(("gnu"   . "https://elpa.gnu.org/packages/")
        ("melpa" . "https://melpa.org/packages/")))

(package-initialize)

(unless package-archive-contents
  (package-refresh-contents))

(unless (package-installed-p 'use-package)
  (package-install 'use-package))

(require 'use-package)
(setq use-package-always-ensure t)

(use-package web-mode
  :mode ("\\.tsx\\'" . web-mode))

(setq counsel-rg-base-command
      "rg --no-heading --line-number --color never --glob '!node_modules/*' --glob '!.git/*' %s .")

(use-package projectile
  :ensure t
  :init
  (projectile-mode +1)  ;; enable globally
  :config
  (setq projectile-enable-caching t))

;; Functions to skip default buffers
(defun my-next-user-buffer ()
  "Switch to the next buffer, skipping special buffers."
  (interactive)
  (let ((start (current-buffer)))
    (next-buffer)
    (while (string-match-p "^\\*" (buffer-name))
      (next-buffer)
      (when (eq (current-buffer) start)
        (message "No more user buffers")
        (cl-return)))))

(defun my-previous-user-buffer ()
  "Switch to the previous buffer, skipping special buffers."
  (interactive)
  (let ((start (current-buffer)))
    (previous-buffer)
    (while (string-match-p "^\\*" (buffer-name))
      (previous-buffer)
      (when (eq (current-buffer) start)
        (message "No more user buffers")
        (cl-return)))))

;; Alt + Left / Right for buffer switching
(global-set-key (kbd "M-<left>")  'my-previous-user-buffer)
(global-set-key (kbd "M-<right>") 'my-next-user-buffer)


(defun my/counsel-find-file-project-root ()
  "Find a file starting from the Projectile project root."
  (interactive)
  (let ((default-directory (projectile-project-root)))
    (counsel-file-jump)))

;; Fuzzy find files
(global-set-key (kbd "C-f") 'my/counsel-find-file-project-root)

(use-package counsel
  :ensure t
  :bind (("C-g" . counsel-rg)))

;; Hide top menu
(menu-bar-mode -1)

;; Display the buffer filename in the header line
(setq-default header-line-format
              '(:eval (if (buffer-file-name)
                          (abbreviate-file-name (buffer-file-name))
                        "%b")))


;; Remove bottom infos
(setq-default mode-line-format nil)

(use-package rust-mode
  :ensure t
  :mode ("\\.rs\\'" . rust-mode))

(global-set-key (kbd "C-o") 'save-buffer)      ;; ^O Write Out
(global-set-key (kbd "C-q") 'save-buffers-kill-terminal) ;; ^X Exit
(global-set-key (kbd "M-u") 'undo)
(global-set-key (kbd "C-u") 'yank)

(defun my-kill-line-or-region ()
  "Kill the whole line if no region is active, otherwise kill the region."
  (interactive)
  (if (use-region-p)
      (kill-region (region-beginning) (region-end))
    (kill-whole-line)))

(global-set-key (kbd "C-k") 'my-kill-line-or-region)



