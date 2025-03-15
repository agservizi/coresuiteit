"use client"

import * as React from "react"
import { Slot } from "@radix-ui/react-slot"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/lib/utils"

const buttonVariants = cva(
  "inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50",
  {
    variants: {
      variant: {
        default: "bg-primary text-primary-foreground hover:bg-primary/90",
        destructive: "bg-destructive text-destructive-foreground hover:bg-destructive/90",
        outline: "border border-input bg-background hover:bg-accent hover:text-accent-foreground",
        secondary: "bg-secondary text-secondary-foreground hover:bg-secondary/80",
        ghost: "hover:bg-accent hover:text-accent-foreground",
        link: "text-primary underline-offset-4 hover:underline",
      },
      size: {
        default: "h-10 px-4 py-2",
        sm: "h-9 rounded-md px-3",
        lg: "h-11 rounded-md px-8",
        icon: "h-10 w-10",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  },
)

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  asChild?: boolean
  themeToggle?: boolean
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, asChild = false, themeToggle = false, ...props }, ref) => {
    // Utilizziamo una variabile più descrittiva e aggiungiamo un commento esplicativo
    // Questo permette di utilizzare un componente personalizzato al posto del button standard
    const ButtonComponent = asChild ? Slot : "button"

    return (
      <ButtonComponent
        className={cn(buttonVariants({ variant, size, className }))}
        ref={ref}
        // Assicuriamo che tutti i props vengano passati correttamente
        {...props}
        // Gestiamo meglio gli eventi di click per supportare funzionalità come i filtri
        onClick={(event) => {
          // Preveniamo il comportamento predefinito solo se necessario
          if (props.onClick) {
            props.onClick(event)
          }

          // Assicuriamo che l'evento non si propaghi in modo indesiderato
          // quando il componente è usato in contesti come PopoverTrigger o DropdownMenuTrigger
          if (asChild && event.currentTarget === event.target) {
            event.stopPropagation()
          }
        }}
        // Aggiungiamo attributi per migliorare l'accessibilità quando usato come pulsante di filtro
        aria-haspopup={props["aria-haspopup"] || (props.id?.includes("filter") ? "true" : undefined)}
        data-state={props["data-state"] || undefined}
      />
    )
  },
)
Button.displayName = "Button"

export { Button, buttonVariants }

