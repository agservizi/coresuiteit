"use client"

import * as React from "react"

import { cn } from "@/lib/utils"

// Update the InputProps interface to include product search specific props
export interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {
  // Base search functionality
  onSearch?: (value: string) => void
  searchResults?: React.ReactNode
  showResults?: boolean
  onResultSelect?: (result: any) => void

  // Product search specific props
  isProductSearch?: boolean
  productSearchFields?: string[] // Fields to search in (e.g., ['name', 'provider'])
  products?: any[] // Array of products to search through
  onProductFound?: (products: any[]) => void // Callback when products are found
}

const Input = React.forwardRef<HTMLInputElement, InputProps>(
  (
    {
      className,
      type,
      onSearch,
      searchResults,
      showResults,
      onResultSelect,
      isProductSearch,
      productSearchFields = ["name", "provider"],
      products = [],
      onProductFound,
      ...props
    },
    ref,
  ) => {
    // State for search value
    const [searchValue, setSearchValue] = React.useState<string>("")
    const inputRef = React.useRef<HTMLInputElement>(null)
    const resultsRef = React.useRef<HTMLDivElement>(null)

    // Combine refs
    const handleRef = (el: HTMLInputElement) => {
      inputRef.current = el
      if (typeof ref === "function") {
        ref(el)
      } else if (ref) {
        ref.current = el
      }
    }

    // Product search function
    const searchProducts = (query: string) => {
      if (!isProductSearch || !products.length || !query.trim()) return []

      const lowerQuery = query.toLowerCase()

      return products.filter((product) => {
        return productSearchFields.some((field) => {
          const fieldValue = product[field]
          return fieldValue && typeof fieldValue === "string" && fieldValue.toLowerCase().includes(lowerQuery)
        })
      })
    }

    // Handle input change
    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
      const value = e.target.value
      setSearchValue(value)

      // If this is a product search, perform the search
      if (isProductSearch) {
        const foundProducts = searchProducts(value)
        if (onProductFound) {
          onProductFound(foundProducts)
        }
      }

      // Call the general onSearch if provided
      if (onSearch) {
        onSearch(value)
      }

      // Call original onChange handler if present
      if (props.onChange) {
        props.onChange(e)
      }
    }

    // Close results when clicking outside
    React.useEffect(() => {
      const handleClickOutside = (event: MouseEvent) => {
        if (
          inputRef.current &&
          resultsRef.current &&
          !inputRef.current.contains(event.target as Node) &&
          !resultsRef.current.contains(event.target as Node)
        ) {
          // Hide results
          if (showResults && onResultSelect) {
            onResultSelect(null)
          }
        }
      }

      document.addEventListener("mousedown", handleClickOutside)
      return () => {
        document.removeEventListener("mousedown", handleClickOutside)
      }
    }, [showResults, onResultSelect])

    return (
      <div className="relative w-full">
        <input
          type={type}
          className={cn(
            "flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 overflow-hidden text-overflow-ellipsis",
            className,
          )}
          ref={handleRef}
          value={props.value !== undefined ? props.value : searchValue}
          onChange={handleInputChange}
          {...props}
        />

        {/* Dropdown for search results */}
        {showResults && searchResults && (
          <div
            ref={resultsRef}
            className="absolute top-full left-0 right-0 mt-1 w-full bg-background rounded-md border shadow-lg z-50 max-h-80 overflow-y-auto"
          >
            {searchResults}
          </div>
        )}
      </div>
    )
  },
)
Input.displayName = "Input"

export { Input }

